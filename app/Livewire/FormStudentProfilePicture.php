<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\Facades\Image;

class FormStudentProfilePicture extends Component
{
    use WithFileUploads;

    public $photo;
    public $photoPreview;

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|max:1024', // 1MB Max
        ]);

        $this->photoPreview = $this->photo->temporaryUrl();
    }

    public function save()
    {
        $this->validate([
            'photo' => 'required|image|max:1024', // 1MB Max
        ]);

        $image = Image::make($this->photo->getRealPath());

        // Crop the image to be a square
        $size = min($image->width(), $image->height());
        $image->crop($size, $size);

        $filename = $this->photo->hashName();
        $image->save(storage_path('app/studentphotos' . $filename));
        $this->photo->store('photos');
    }

    public function render()
    {
        return view('livewire.form-student-profile-picture');
    }
}
