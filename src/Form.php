<?php

namespace App\Crud;
class Form
{
    private bool $isFormed;
    private string  $formString;
    public function __construct()
    {
        $this->isFormed = false;
        $this->formString = "";
    }

    public function createForm(string $action, string $method, string $formClass){

        if (!$this->isFormed){
            $this->formString .= "<form action='$action' method='$method'>";
            $this->formString .= "<div class='$formClass'>";
            $this->isFormed = true;
        }
        else {
            throw new \Exception("On ne peut pas lancer plusieurs createForm() !");
        }

    }

    public function createInput(string $inputName, string $type, string $value, string $class, string $name, string $labelClass)
    {
        if ($this->isFormed){
            if ( $type !== "checkbox" && $type !== "radio") {
                $this->formString .= "<label class='$labelClass' for='$name'>$inputName</label>";
                $this->formString .= "<input  type='$type' class='$class' name='$name' value='$value'>";
            }
            else {
                throw new \Exception("Cette methode ne supporte pas le type checkbox et radio veuillez utiliser createCheckBox() ou doubleRadio().");
            }
        }
        else {
            throw new \Exception("Il faut lancer la methode createForm() avant de créer quoique ce soit !");
        }
    }

    public function createSelect(string $inputName, string $value, array $entitiesValues, string $class, string $name, string $labelClass, bool $isSized = false  ){

        $size= count($entitiesValues);

        if ($this->isFormed){
            $this->formString .= "<label class='$labelClass' for='$name'>$inputName</label>";
            if ($isSized){
                $this->formString .= "<select class='$class' name='$name' size='$size'>";
            }
            else{
                $this->formString .= "<select class='$class' name='$name'>";
            }

                foreach ($entitiesValues as $entitiesValue) {
                    if ($value !== $entitiesValue) {
                        $this->formString .= "<option value='$entitiesValue'>$entitiesValue</option>";
                    }
                    else
                        $this->formString .= "<option value='$entitiesValue' selected>$entitiesValue</option>";
                    }
            $this->formString .= "</select>";
        }
        else {
            throw new \Exception("Il faut lancer la methode createForm() avant de créer quoique ce soit !");
        }
    }

    public function createCheckBox(array $cBValues, string $name, string $class, string $laberClass, string $values){

        $name = $name . "[]";
        if ($this->isFormed){
            foreach ($cBValues as $cBValue){
                    $checked = str_contains($values, $cBValue)  ?  "checked" : "";
                    $this->formString .= "<input  class='$class' name='$name' type='checkbox' value='$cBValue' $checked>";
                    $this->formString .= "<label class='$laberClass' for='$name'>$cBValue</label>";
            }
        }
        else {
            throw new \Exception("Il faut lancer la methode createForm() avant de créer quoique ce soit !");
        }
    }

    public function createDoubleRadio(string $labelAName, string $labelBName, string $value, string $radioAValue, string $radioBValue, string $class, string $labelClass, string $name ){

        $checkedA = $radioAValue === $value ? "checked" : "";
        $checkedB = $radioBValue === $value ? "checked" : "";

        if ($this->isFormed){
            $this->formString .=
                "<div> 
                      <input class='$class' type='radio' name='$name' value='$radioAValue' $checkedA>
                      <label class='$labelClass' for='$name'>$labelAName</label>
                </div>
                <div> 
                      <input class='$class' type='radio' name='$name' value='$radioBValue' $checkedB>
                      <label class='$labelClass' for='$name'>$labelBName</label>
                </div>";

        }
        else {
            throw new \Exception("Il faut lancer la methode createForm() avant de créer quoique ce soit !");
        }
    }

    public function createHidden(string $value, string $name){
        if ($this->isFormed) {

            $this->formString .= "<input type='hidden' name='$name' value='$value'>";
        }
        else {
            throw new \Exception("Il faut lancer la methode createForm() avant de créer quoique ce soit !");
        }
    }


    public function createSubmit(string $divClass, string $buttonClass, string $submitValue){
        if ($this->isFormed) {

            $this->formString .= "<div class='$divClass'><button class='$buttonClass' type='submit'>$submitValue</button></div></div>";
        }
        else {
            throw new \Exception("Il faut lancer la methode createForm() avant de créer quoique ce soit !");
        }

        echo $this->formString;
    }

}