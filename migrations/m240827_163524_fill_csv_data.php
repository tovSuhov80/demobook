<?php

use yii\db\Migration;

class m240827_163524_fill_csv_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $csvDataPath = Yii::getAlias('@app/migrations').DIRECTORY_SEPARATOR.'csv-data'.DIRECTORY_SEPARATOR;

        //books
        $csvData = $this->parseCsvToArray($csvDataPath.'books.csv');
        foreach ($csvData as $data) {
            $this->execute("INSERT INTO {{%books}} (id, user_id, isbn, title,description, release_year, photo_url) 
    values (:id, :user_id, :isbn, :title, :description, :release_year, :photo_url);",
                [
                    ':id' => $data['id'],
                    ':user_id' => $data['user_id'],
                    ':isbn' => $data['isbn'],
                    ':title' => trim($data['title']),
                    ':description' => empty(trim($data['description'])) ? null : trim($data['description']),
                    ':release_year' => $data['release_year'],
                    ':photo_url' => $data['photo_url']
                ]);
        }

        //authors
        $csvData = $this->parseCsvToArray($csvDataPath.'authors.csv');
        foreach ($csvData as $data) {
            $this->execute("INSERT INTO {{%authors}} (id, first_name, last_name, middle_name) 
    values (:id, :first_name, :last_name, :middle_name);",
                [
                    ':id' => $data['id'],
                    ':first_name' => trim($data['first_name']),
                    ':last_name' => trim($data['last_name']),
                    ':middle_name' => empty(trim($data['middle_name'])) ? null : trim($data['middle_name'])
                ]);
        }

        //books-authors
        $csvData = $this->parseCsvToArray($csvDataPath.'book_authors.csv');
        foreach ($csvData as $data) {
            $this->execute("INSERT INTO {{%book_authors}} (book_id, author_id) 
    values (:book_id, :author_id);",
                [
                    ':book_id' => $data['book_id'],
                    ':author_id' => $data['author_id']
                ]);
        }


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("TRUNCATE TABLE {{%books}};");
    }

    protected function parseCsvToArray($filename): array
    {
        $result = [];

        if (($handle = fopen($filename, "r")) !== FALSE) {
            $headers = fgetcsv($handle, 1000, ";");

            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $row = [];
                foreach ($headers as $index => $header) {
                    $row[$header] = $data[$index];
                }
                $result[] = $row;
            }

            fclose($handle);
        }

        return $result;
    }

}
