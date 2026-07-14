<?php

namespace App\Data;

class Game
{
    public function __construct(

        public string $id,

        public string $provider,

        public string $title,

        public string $slug,

        public string $description,

        public string $category,

        public string $orientation,

        public string $cover,

        public string $icon,

        public string $playUrl,

        public int $width,

        public int $height,

        public float $quality,

    ) {
    }
}