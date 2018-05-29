<?php

namespace App\Entity;

class Answer4You
{
    private $id;

    private $filename;

    private $createdDate;

    private $department;

    private $callsMissed;

    /**
     * @param null|string $filename
     * @return Answer4You
     */
    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @param \DateTimeInterface|null $createdDate
     * @return Answer4You
     */
    public function setCreatedDate(?\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * @param null|string $department
     * @return Answer4You
     */
    public function setDepartment(?string $department): self
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @param int|null $callsMissed
     * @return Answer4You
     */
    public function setCallsMissed(?int $callsMissed): self
    {
        $this->callsMissed = $callsMissed;

        return $this;
    }
}
