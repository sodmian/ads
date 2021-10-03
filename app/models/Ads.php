<?php

namespace Models;

class Ads extends BaseModel
{
    public function getAds(): bool|array
    {
        $sql = "SELECT * FROM `list_ads` ORDER BY `id` DESC";
        $sth = $this->db->prepare($sql);
        $sth->execute();
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function uploadAds(array $data): bool
    {
        $sql = "INSERT INTO `list_ads` (`text`, `contacts`) VALUES (:text, :contacts)";
        $sth = $this->db->prepare($sql);
        $sth->bindParam(':text', $data['text'], \PDO::PARAM_STR);
        $sth->bindParam(':contacts', $data['contacts'], \PDO::PARAM_STR);
        $sth->execute();
        return $this->db->lastInsertId();
    }
}