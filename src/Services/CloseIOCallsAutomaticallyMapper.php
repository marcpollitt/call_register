<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 12/04/2018
 * Time: 11:48
 */

namespace App\Services;

use App\Entity\Calls;

/**
 * Class CloseIOCallsAutomaticallyMapper
 * @package App\Services
 */
class CloseIOCallsAutomaticallyMapper
{

    const DATEFORMAT = ['date_updated','date_created'];

    /** @var Calls */
    private $calls;

    public function setCalls(array $row, array $skipKeys = []){

        $this->calls = new Calls;

        $row['date_updated'] = new \DateTime($row['date_updated']);
        $row['date_created'] = new \DateTime($row['date_created']);

        $this->calls->setDuration($row['duration'])
            ->setContactId($row['contact_id'])
            ->setType($row['_type'])
            ->setCreatedByName($row['created_by_name'])
            ->setCreatedBy($row['created_by'])
            ->setVoicemailUrl($row['voicemail_url'])
            ->setDateUpdated($row['date_updated'])
            ->setCloseId($row['id'])
            ->setUpdatedByName($row['updated_by_name'])
            ->setUsers($row['users'])
            ->setUserId($row['user_id'])
            ->setVoicemailDuration($row['voicemail_duration'])
            ->setTransferredFrom($row['transferred_from'])
            ->setNote($row['note'])
            ->setSource($row['source'])
            ->setHasRecording($row['has_recording'])
            ->setDialerId($row['dialer_id'])
            ->setUserName($row['user_name'])
            ->setStatus($row['status'])
            ->setDirection($row['direction'])
            ->setLocalPhoneFormatted($row['local_phone_formatted'])
            ->setUpdatedBy($row['updated_by'])
            ->setRemotePhone(md5($row['remote_phone']))
            ->setRemotePhoneFormatted(md5($row['remote_phone_formatted']))
            ->setOrganizationId($row['organization_id'])
            ->setLocalPhone($row['local_phone'])
            ->setLeadId($row['lead_id'])
            ->setTransferredTo($row['transferred_to'])
            ->setDateCreated($row['date_created'])
            ->setRecordingUrl($row['recording_url']);

        return $this;
    }

    public function getCalls(): Calls
    {
        return $this->calls;

    }
}