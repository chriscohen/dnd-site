<?php declare(strict_types=1);

namespace App\Models;

class SpellComponentType extends AbstractModel
{
    public $timestamps = false;
    public $incrementing = false;

    public function __construct(
        protected ?string $id = null,
        protected ?string $name = null,
    ) {
        parent::__construct();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): SpellComponentType
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): SpellComponentType
    {
        $this->name = $name;
        return $this;
    }
}
