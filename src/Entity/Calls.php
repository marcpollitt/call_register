<?php

namespace App\Entity;

/**
 * Calls
 */
class Calls
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string|null
     */
    private $voicemailUrl;

    /**
     * @var \DateTime
     */
    private $dateUpdated;

    /**
     * @var string
     */
    private $createdByName;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $contactId;

    /**
     * @var int
     */
    private $duration;

    /**
     * @var string
     */
    private $closeId;

    /**
     * @var string
     */
    private $updatedByName;

    /**
     * @var array
     */
    private $users;

    /**
     * @var string
     */
    private $userId;

    /**
     * @var int
     */
    private $voicemailDuration;

    /**
     * @var string|null
     */
    private $transferredFrom;

    /**
     * @var string
     */
    private $createdBy;

    /**
     * @var string|null
     */
    private $note;

    /**
     * @var string
     */
    private $source;

    /**
     * @var bool
     */
    private $hasRecording;

    /**
     * @var string|null
     */
    private $dialerId;

    /**
     * @var string
     */
    private $userName;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $direction;

    /**
     * @var string
     */
    private $localPhoneFormatted;

    /**
     * @var string
     */
    private $updatedBy;

    /**
     * @var string
     */
    private $organizationId;

    /**
     * @var string
     */
    private $localPhone;

    /**
     * @var string
     */
    private $leadId;

    /**
     * @var string|null
     */
    private $transferredTo;

    /**
     * @var \DateTime
     */
    private $dateCreated;

    /**
     * @var string
     */
    private $recordingUrl;

    /**
     * Set voicemailUrl.
     *
     * @param string|null $voicemailUrl
     *
     * @return Calls
     */
    public function setVoicemailUrl($voicemailUrl = null)
    {
        $this->voicemailUrl = $voicemailUrl;

        return $this;
    }

    /**
     * Set dateUpdated.
     *
     * @param \DateTime $dateUpdated
     *
     * @return Calls
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    /**
     * Set createdByName.
     *
     * @param string $createdByName
     *
     * @return Calls
     */
    public function setCreatedByName($createdByName)
    {
        $this->createdByName = $createdByName;

        return $this;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return Calls
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Set contactId.
     *
     * @param string $contactId
     *
     * @return Calls
     */
    public function setContactId($contactId)
    {
        $this->contactId = $contactId;

        return $this;
    }

    /**
     * Set duration.
     *
     * @param int $duration
     *
     * @return Calls
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Set closeId.
     *
     * @param string $closeId
     *
     * @return Calls
     */
    public function setCloseId($closeId)
    {
        $this->closeId = $closeId;

        return $this;
    }

    /**
     * Set updatedByName.
     *
     * @param string $updatedByName
     *
     * @return Calls
     */
    public function setUpdatedByName($updatedByName)
    {
        $this->updatedByName = $updatedByName;

        return $this;
    }

    /**
     * Set users.
     *
     * @param array $users
     *
     * @return Calls
     */
    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Set userId.
     *
     * @param string $userId
     *
     * @return Calls
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Set voicemailDuration.
     *
     * @param int $voicemailDuration
     *
     * @return Calls
     */
    public function setVoicemailDuration($voicemailDuration)
    {
        $this->voicemailDuration = $voicemailDuration;

        return $this;
    }

    /**
     * Set transferredFrom.
     *
     * @param string|null $transferredFrom
     *
     * @return Calls
     */
    public function setTransferredFrom($transferredFrom = null)
    {
        $this->transferredFrom = $transferredFrom;

        return $this;
    }

    /**
     * Set createdBy.
     *
     * @param string $createdBy
     *
     * @return Calls
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Set note.
     *
     * @param string|null $note
     *
     * @return Calls
     */
    public function setNote($note = null)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Set source.
     *
     * @param string $source
     *
     * @return Calls
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Set hasRecording.
     *
     * @param bool $hasRecording
     *
     * @return Calls
     */
    public function setHasRecording($hasRecording)
    {
        $this->hasRecording = $hasRecording;

        return $this;
    }

    /**
     * Set dialerId.
     *
     * @param string|null $dialerId
     *
     * @return Calls
     */
    public function setDialerId($dialerId = null)
    {
        $this->dialerId = $dialerId;

        return $this;
    }

    /**
     * Set userName.
     *
     * @param string $userName
     *
     * @return Calls
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Set status.
     *
     * @param string $status
     *
     * @return Calls
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Set direction.
     *
     * @param string $direction
     *
     * @return Calls
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;

        return $this;
    }

    /**
     * Set localPhoneFormatted.
     *
     * @param string $localPhoneFormatted
     *
     * @return Calls
     */
    public function setLocalPhoneFormatted($localPhoneFormatted)
    {
        $this->localPhoneFormatted = $localPhoneFormatted;

        return $this;
    }

    /**
     * Set updatedBy.
     *
     * @param string $updatedBy
     *
     * @return Calls
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Set organizationId.
     *
     * @param string $organizationId
     *
     * @return Calls
     */
    public function setOrganizationId($organizationId)
    {
        $this->organizationId = $organizationId;

        return $this;
    }

    /**
     * Set localPhone.
     *
     * @param string $localPhone
     *
     * @return Calls
     */
    public function setLocalPhone($localPhone)
    {
        $this->localPhone = $localPhone;

        return $this;
    }

    /**
     * Set leadId.
     *
     * @param string $leadId
     *
     * @return Calls
     */
    public function setLeadId($leadId)
    {
        $this->leadId = $leadId;

        return $this;
    }

    /**
     * Set transferredTo.
     *
     * @param string|null $transferredTo
     *
     * @return Calls
     */
    public function setTransferredTo($transferredTo = null)
    {
        $this->transferredTo = $transferredTo;

        return $this;
    }

    /**
     * Set dateCreated.
     *
     * @param \DateTime $dateCreated
     *
     * @return Calls
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Set recordingUrl.
     *
     * @param string $recordingUrl
     *
     * @return Calls
     */
    public function setRecordingUrl($recordingUrl)
    {
        $this->recordingUrl = $recordingUrl;

        return $this;
    }
}
