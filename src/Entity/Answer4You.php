<?php

namespace App\Entity;

class Answer4You
{
    private $id;

    private $filename;

    private $createdDate;

    private $department;

    private $callsMissed;

    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function setCreatedDate(?\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function setDepartment(?string $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function setCallsMissed(?int $callsMissed): self
    {
        $this->callsMissed = $callsMissed;

        return $this;
    }
}
