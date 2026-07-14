<?php

namespace App\Data;

class Game
{
    public function __construct(
        public string $id,
        public string $title,
        public string $namespace,
        public ?string $description,
        public string $category,
        public string $orientation,
        public float $quality,
        public int $width,
        public int $height,
        public string $banner,
        public string $icon,
        public string $url
    ) {}
}
