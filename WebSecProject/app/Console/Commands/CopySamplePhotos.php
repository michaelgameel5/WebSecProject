<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CopySamplePhotos extends Command
{
    protected $signature = 'photos:copy-samples';
    protected $description = 'Copy sample product photos to storage';

    public function handle()
    {
        $sourceDir = public_path('sample-photos');
        $targetDir = storage_path('app/public/products');

        // Create target directory if it doesn't exist
        if (!File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        // Sample photos data
        $photos = [
            'laptop.jpg' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853',
            'smartphone.jpg' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9',
            'headphones.jpg' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e'
        ];

        foreach ($photos as $filename => $url) {
            $targetPath = $targetDir . '/' . $filename;
            
            // Download and save the image
            $imageContent = file_get_contents($url);
            if ($imageContent !== false) {
                File::put($targetPath, $imageContent);
                $this->info("Copied {$filename} to storage");
            } else {
                $this->error("Failed to download {$filename}");
            }
        }

        $this->info('Sample photos have been copied to storage');
    }
} 