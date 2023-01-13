<?php

namespace App\Crud;
class User
{

    private int $id;
    private string $username;
    private string $password;
    private string $job;
    private string $country;
    private string $sex;
    private string $language;
    private string $leisure;

    public function createUserToForm(array $array, string $verb){
        foreach ($array as $key => $value) {
            if (!empty($value)){
                $method = 'set' . ucfirst($key); // username => Usename => setUsername  ( $_Post["username"] = "patrick" )
                if (method_exists($this, $method)) {
                    if (is_array($value)){
                        $value = implode(",", $value );
                        $this->$method($value);
                    }
                    else{
                        $this->$method($value); // $this->setUsername("Patrick") // EN PHP on peut appeller une method
                        // à partir d'une string.
                    }
                }
            }
            else{
                $message = "Formulaire incomplet";
                header("Location:http://localhost/crud/crudNoMvc/?crud=$verb&error=$message");
                exit;
            }
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getJob(): string
    {
        return $this->job;
    }

    /**
     * @param string $job
     */
    public function setJob(string $job): void
    {
        $this->job = $job;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return bool
     */
    public function getSex(): string
    {
        return $this->sex;
    }

    /**
     * @param bool $sex
     */
    public function setSex(string $sex): void
    {
        $this->sex = $sex;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @param string $language
     */
    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }

    /**
     * @return array
     */
    public function getLeisure(): string
    {
        return $this->leisure;
    }

    /**
     * @param array $leisure
     */
    public function setLeisure(string $leisure): void
    {
        $this->leisure = $leisure;
    }



}