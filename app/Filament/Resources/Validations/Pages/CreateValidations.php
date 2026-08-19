<?php

namespace App\Filament\Resources\Validations\Pages;

use App\Filament\Resources\Validations\ValidationsResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Http\Response;

class CreateValidations extends CreateRecord
{
    protected static string $resource = ValidationsResource::class;
    
    /**
     * Handle the creation of a new validation record.
     * Since validations are automatically created with ideas, we'll redirect to index.
     */
    public function create(bool $another = false): void
    {
        $this->authorizeAccess();
        
        // Redirect to the validations index page
        $this->redirect(ValidationsResource::getUrl('index'));
    }
    
    /**
     * Get the URL to redirect to after creation.
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    /**
     * Get the created notification message.
     */
    protected function getCreatedNotificationMessage(): ?string
    {
        return 'Validations are automatically created when a new idea is submitted.';
    }
}
