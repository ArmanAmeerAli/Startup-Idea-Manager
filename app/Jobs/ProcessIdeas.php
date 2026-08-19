<?php

namespace App\Jobs;

use App\Models\Ideas;
use App\Models\Validations;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class ProcessIdeas implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Indicate if the job should be dispatched immediately.
     *
     * @var bool
     */
    public $dispatchAfterCommit = true;

    /**
     * The idea instance.
     *
     * @var \App\Models\Ideas
     */
    protected $idea;

    /**
     * The validation instance.
     *
     * @var \App\Models\Validations
     */
    protected $validation;

    /**
     * Create a new job instance.
     *
     * @param  \App\Models\Ideas  $idea
     * @param  \App\Models\Validations  $validation
     * @return void
     */
    public function __construct(Ideas $idea, Validations $validation)
    {
        $this->idea = $idea;
        $this->validation = $validation;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Starting ProcessIdeas job', [
            'idea_id' => $this->idea->id,
            'validation_id' => $this->validation->id
        ]);
        
        DB::beginTransaction();
        
        try {
            // Update status to processing in both models
            $this->idea->update(['status' => 'Processing']);
            $this->validation->update(['status' => 'processing']);
            
            Log::info('Updated status to Processing', [
                'idea_id' => $this->idea->id,
                'validation_id' => $this->validation->id
            ]);
            
            // Call the AI validation API
            Log::info('Calling AI validation API', [
                'idea_id' => $this->idea->id,
                'title' => $this->idea->title
            ]);
            
            $aiResponse = $this->callAiValidationApi(
                $this->idea->title,
                $this->idea->description
            );
            
            Log::info('Received AI response', [
                'idea_id' => $this->idea->id,
                'response' => $aiResponse
            ]);
            
            // Update validation record with AI response
            $this->validation->update([
                'ai_score' => $aiResponse['ai_score'],
                'ai_feedback' => $aiResponse['ai_feedback'],
                'ai_suggestions' => $aiResponse['ai_suggestions'],
                'ai_model' => 'dummy-ai-model-v1',
                'status' => $aiResponse['status']
            ]);
            
            // Update idea with AI feedback and status
            $this->idea->update([
                'ai_score' => $aiResponse['ai_score'],
                'ai_feedback' => $aiResponse['ai_feedback'],
                'status' => $aiResponse['status'] === 'approved' ? 'Validated' : 'Rejected'
            ]);
            
            DB::commit();
            
            // Send success notification
            $user = User::find($this->idea->user_id);
            if ($user) {
                Notification::make()
                    ->title('Idea Processed Successfully')
                    ->body('Your idea has been processed and ' . ($aiResponse['status'] === 'approved' ? 'approved' : 'rejected') . '.')
                    ->success()
                    ->sendToDatabase($user);
            }
            
            Log::info('Successfully processed idea', [
                'idea_id' => $this->idea->id,
                'status' => $aiResponse['status'] ?? 'unknown'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            $errorMessage = 'Error processing idea validation: ' . $e->getMessage();
            
            Log::error($errorMessage, [
                'idea_id' => $this->idea->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Update status to indicate failure
            $this->idea->update(['status' => 'Failed']);
            $this->validation->update([
                'status' => 'failed',
                'ai_feedback' => $errorMessage,
            ]);
            
            // Send error notification
            $user = User::find($this->idea->user_id);
            if ($user) {
                Notification::make()
                    ->title('Idea Processing Failed')
                    ->body('There was an error processing your idea. Please try again.')
                    ->danger()
                    ->sendToDatabase($user);
            }
            
            throw $e; // Re-throw to allow job retries if configured
        }
    }
    
    /**
     * Call the AI validation API
     * 
     * @param string $title
     * @param string $description
     * @return array
     */
    private function callAiValidationApi(string $title, string $description): array
    {
        try {
            // This is a dummy API call - replace with actual AI service
            $response = Http::timeout(60)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . config('services.ai.api_key'),
                ])
                ->post('https://api.example.com/validate-idea', [
                    'title' => $title,
                    'description' => $description,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Return a consistent response structure
                return [
                    'ai_score' => $data['score'] ?? rand(1, 100), // Default random score for demo
                    'ai_feedback' => $data['feedback'] ?? 'No feedback provided',
                    'ai_suggestions' => $data['suggestions'] ?? 'No suggestions provided',
                    'status' => $data['status'] ?? 'approved', // Default to approved for demo
                ];
            }
            
            throw new \Exception('AI API request failed with status: ' . $response->status());
            
        } catch (\Exception $e) {
            Log::error('AI Validation API error: ' . $e->getMessage());
            
            // Return a safe default response on error
            return [
                'ai_score' => 0,
                'ai_feedback' => 'Error during AI validation: ' . $e->getMessage(),
                'ai_suggestions' => 'Please try again later or contact support.',
                'status' => 'rejected',
            ];
        }
    }
}
