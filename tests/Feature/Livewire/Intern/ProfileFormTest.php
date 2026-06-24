<?php

namespace Tests\Feature\Livewire\Intern;

use App\Livewire\Intern\ProfileForm;
use App\Models\InternProfile;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileFormTest extends TestCase
{
    public function test_mount_populates_existing_profile(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create([
            'user_id' => $intern->id,
            'full_name' => 'Test User',
            'institution_name' => 'Test University',
        ]);

        Livewire::actingAs($intern)
            ->test(ProfileForm::class)
            ->assertSet('full_name', 'Test User')
            ->assertSet('institution_name', 'Test University');
    }

    public function test_save_updates_profile(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);

        Livewire::actingAs($intern)
            ->test(ProfileForm::class)
            ->set('full_name', 'Updated Name')
            ->set('institution_name', 'Updated University')
            ->set('institution_type', 'university')
            ->set('major', 'Computer Science')
            ->set('student_id', 'STU-999')
            ->call('save');

        $this->assertDatabaseHas('intern_profiles', [
            'user_id' => $intern->id,
            'full_name' => 'Updated Name',
            'institution_name' => 'Updated University',
        ]);
    }

    public function test_save_validates_required_fields(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);

        Livewire::actingAs($intern)
            ->test(ProfileForm::class)
            ->set('full_name', '')
            ->set('institution_name', '')
            ->set('institution_type', '')
            ->set('major', '')
            ->set('student_id', '')
            ->call('save')
            ->assertHasErrors([
                'full_name', 'institution_name', 'institution_type', 'major', 'student_id',
            ]);
    }

    public function test_save_uploads_photo(): void
    {
        Storage::fake('private');

        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);

        $photo = UploadedFile::fake()->image('photo.jpg');

        Livewire::actingAs($intern)
            ->test(ProfileForm::class)
            ->set('full_name', 'Test')
            ->set('institution_name', 'Univ')
            ->set('institution_type', 'university')
            ->set('major', 'CS')
            ->set('student_id', 'STU-001')
            ->set('photo', $photo)
            ->call('save');

        $profile = InternProfile::where('user_id', $intern->id)->first();
        $this->assertNotNull($profile->photo_url);
        Storage::disk('private')->assertExists($profile->photo_url);
    }

    public function test_save_uploads_cv(): void
    {
        Storage::fake('private');

        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);

        $cv = UploadedFile::fake()->create('cv.pdf', 100);

        Livewire::actingAs($intern)
            ->test(ProfileForm::class)
            ->set('full_name', 'Test')
            ->set('institution_name', 'Univ')
            ->set('institution_type', 'university')
            ->set('major', 'CS')
            ->set('student_id', 'STU-001')
            ->set('cv', $cv)
            ->call('save');

        $profile = InternProfile::where('user_id', $intern->id)->first();
        $this->assertNotNull($profile->cv_url);
        Storage::disk('private')->assertExists($profile->cv_url);
    }

    public function test_save_validates_file_type(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);

        $invalidFile = UploadedFile::fake()->create('document.txt', 100);

        Livewire::actingAs($intern)
            ->test(ProfileForm::class)
            ->set('full_name', 'Test')
            ->set('institution_name', 'Univ')
            ->set('institution_type', 'university')
            ->set('major', 'CS')
            ->set('student_id', 'STU-001')
            ->set('cv', $invalidFile)
            ->call('save')
            ->assertHasErrors(['cv']);
    }
}
