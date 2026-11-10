<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use App\Models\Area;
use App\Models\TrainingCenter;
use App\Models\Computer;
use App\Models\Image;

class ImageUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_image_polymorphic_relationship_with_area()
    {
        $area = Area::create(['name' => 'Sistemas y Desarrollo']);

        $image = $area->image()->create([
            'url' => 'https://res.cloudinary.com/demo/image/upload/sample.jpg',
            'public_id' => 'sample',
        ]);

        $this->assertDatabaseHas('images', [
            'id' => $image->id,
            'url' => 'https://res.cloudinary.com/demo/image/upload/sample.jpg',
            'imageable_id' => $area->id,
            'imageable_type' => $area->getMorphClass(),
        ]);

        $this->assertEquals($area->id, $image->imageable->id);
        $this->assertEquals('Sistemas y Desarrollo', $area->image->imageable->name);
    }

    public function test_image_polymorphic_relationship_with_training_center()
    {
        $center = TrainingCenter::create([
            'name' => 'Centro de Biotecnología Agropecuaria',
            'location' => 'Mosquera',
        ]);

        $image = $center->image()->create([
            'url' => 'https://res.cloudinary.com/demo/image/upload/cba.jpg',
            'public_id' => 'cba',
        ]);

        $this->assertDatabaseHas('images', [
            'id' => $image->id,
            'imageable_id' => $center->id,
            'imageable_type' => $center->getMorphClass(),
        ]);

        $this->assertEquals('Mosquera', $image->imageable->location);
    }

    public function test_image_polymorphic_relationship_with_computer()
    {
        $computer = Computer::create([
            'number' => 'PC-402',
            'brand' => 'Lenovo',
        ]);

        $image = $computer->image()->create([
            'url' => 'https://res.cloudinary.com/demo/image/upload/pc402.jpg',
            'public_id' => 'pc402',
        ]);

        $this->assertDatabaseHas('images', [
            'id' => $image->id,
            'imageable_id' => $computer->id,
            'imageable_type' => $computer->getMorphClass(),
        ]);

        $this->assertEquals('Lenovo', $image->imageable->brand);
    }

    public function test_validation_fails_when_no_image_or_entity_provided()
    {
        $response = $this->postJson('/api/images', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['image', 'imageable_type', 'imageable_id']);
    }

    public function test_validation_fails_for_invalid_entity_type()
    {
        Config::set('cloudinary.cloud_url', 'cloudinary://key:secret@cloud');

        // create fake file without requiring GD extension
        $file = UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg');

        $response = $this->postJson('/api/images', [
            'image' => $file,
            'imageable_type' => 'InvalidEntity',
            'imageable_id' => 1,
        ]);

        $response->assertStatus(422)
            ->assertJsonFragment(['error' => "El tipo 'InvalidEntity' no es válido. Debe escoger entre: Area, TrainingCenter o Computer."]);
    }

    public function test_validation_fails_when_entity_record_not_found()
    {
        Config::set('cloudinary.cloud_url', 'cloudinary://key:secret@cloud');

        $file = UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg');

        $response = $this->postJson('/api/images', [
            'image' => $file,
            'imageable_type' => 'Area',
            'imageable_id' => 9999,
        ]);

        $response->assertStatus(404)
            ->assertJsonFragment(['error' => 'No se encontró el registro con ID 9999 para Area.']);
    }
}
