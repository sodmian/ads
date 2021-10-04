<?php

namespace Controllers;

use Addons\Token\Token;
use http\Exception\RuntimeException;
use Models;

class Ads extends \Core\Presenter
{
    public function csvPreload()
    {
        if ($this->csvPreValidation()) {
            $tmpFile = $_FILES['file'];

            $file = fopen($tmpFile['tmp_name'], 'r');
            $key = 0;
            while (($line = fgetcsv($file))) {
                ++$key;
                $data = explode(';', $line[0]);
                if (!empty($data)) {
                    $this->csvValidation($data, $key);
                }
            }
            fclose($file);

            echo json_encode([
                'success' => empty(Log::$errors),
                'errors' => Log::$errors
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'errors' => ["Не удалось проверить файл."]
            ]);
        }
    }

    public function csvUpload()
    {
        if ($this->csvPreValidation()) {
            $this->load->model('Ads');

            $tmpFile = $_FILES['file'];

            $file = fopen($tmpFile['tmp_name'], 'r');
            while (($line = fgetcsv($file))) {
                $data = explode(';', $line[0]);
                if (!empty($data)) {
                    $this->model->Ads->uploadAds(['text' => $data[0] ?? '', 'contacts' => $data[1] ?? '']);
                }
            }
            fclose($file);

            echo json_encode([
                'success' => true,
                'errors' => [],
                'data' => $this->model->Ads->getAds()
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'errors' => ["Не удалось загрузить файл."]
            ]);
        }
    }

    public function csvPreValidation(): bool
    {
        if (isset($_FILES["file"]) && (new Token())->isValidateToken($_REQUEST['token'])) {
            if (!is_uploaded_file($_FILES['file']['tmp_name'])) return false;
            $ext = substr($_FILES['file']['name'], 1 + strrpos($_FILES['file']['name'], "."));
            return $ext == "csv";
        }
        return false;
    }

    public function csvValidation(array $data, int $key)
    {
        if (!isset($data[1])) {
            Log::add("Строка $key - некорректный формат разделителя\n");
            return;
        }

        list($header, $contacts) = $data;

        if (!empty($header)) {
            if (mb_strlen($header) > (int)$_ENV['APP_CSV_HEADER_LEN']) {
                Log::add("Строка $key - превышен лимит символов в заголовке `$header`\n");
            }
            if (mb_strlen($contacts) > (int)$_ENV['APP_CSV_CONTACTS_LEN']) {
                Log::add("Строка $key - превышен лимит символов в контактных данных `$contacts`\n");
            }
        }
    }
}