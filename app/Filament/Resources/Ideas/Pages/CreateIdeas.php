<?php

namespace App\Filament\Resources\Ideas\Pages;

use App\Filament\Resources\Ideas\IdeasResource;
use App\Jobs\ProcessIdeas;
use App\Models\Validations;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CreateIdeas extends CreateRecord
{
    protected static string $resource = IdeasResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        $data['status'] = 'Pending'; // Set initial status
        return $data;
    }

    protected function afterCreate(): void
    {
        Log::info('afterCreate start', ['idea_id' => $this->record->id]);

        $validation = Validations::create([
            'idea_id' => $this->record->id,
            'status' => 'pending',
            'ai_model' => 'dummy-ai-model-v1',
        ]);

        Log::info('validation created', ['validation_id' => $validation->id]);

        try {
            Log::info('About to dispatch ProcessIdeas job', ['idea_id' => $this->record->id, 'validation_id' => $validation->id]);

            // Normal (queued) dispatch
            ProcessIdeas::dispatch($this->record, $validation);

            // For testing immediate execution (uncomment if you need to test execution without queue):
            // Bus::dispatchSync(new ProcessIdeas($this->record, $validation));

            Log::info('Dispatch call done', ['idea_id' => $this->record->id, 'validation_id' => $validation->id]);
        } catch (\Throwable $e) {
            // Log the full error with stack trace
            Log::error('Failed to process idea creation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'idea_id' => $this->record->id ?? null,
                'record_data' => $this->record->toArray()
            ]);
            
            // Update validation status if it was created
            if (isset($validation)) {
            $validation->update(['status' => 'failed']);
            throw $e;
        }

        $this->record->refresh();
        Log::info('afterCreate end', ['idea_id' => $this->record->id]);
        }
    }
}