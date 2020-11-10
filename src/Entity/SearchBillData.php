<?php


namespace App\Entity;


class SearchBillData
{
    private User $user;

    /**
     * @return mixed
     */
    public function getUser() :User
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }
}