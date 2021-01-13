<?php

/**
 * @author Reyes Yam <reyesyamm@gmail.com>
 * @copyright (c) 2018, Reyes Yam
 * @version 1.0
 **/

class BaseForm{
    
    var $list = [];
    
    
    public function __construct() {
        
    }

    public function beginForm($id, $multipart = false){
        echo "<form id='$id'>";
    }

    public function endForm(){
        echo "</form>";
    }

    public function inputHidden($id, $name, $value){
        echo "<input type='hidden' name='$name' value='$value' id='$id' />";
    }
    
    public function bs4FormInput(
            $id,
            $label = "Input",
            $name = null,
            $defValue = null,
            $isRequired = false,
            $type = "text",
            $class = "",
            $placeholder = '',
            $enabled = true
            ){
        
        $this->list[$id] = [
            'typeForm' => 'input',
            'id'=>$id, 
            'name' => $name,
            'label'=>$label,
            'value' => $defValue, 
            'required' => $isRequired,
            'type' => $type,
            'class' => $class,
            'placeholder' => $placeholder,
            'enabled'=>$enabled
            ];
    }
    
    public function render($id){
        $frm = $this->list[$id];
        if($frm['typeForm'] == 'input'){
            echo "<div class='form-group'>"
                . "<label for='".($frm['name'] != null ? $frm['name'] : "")."'>".$frm['label'].( $frm['required'] ? " <span id='span_required_".($frm['id'])."' class='text-danger'>*</span>" : "" ) ."</label>"
                . "<input "
                    . "type='".$frm['type']."' "
                    . "class='form-control ".$frm['class']."' "
                    . ($frm['value'] != null ? " value='".$frm['value']."' " : "")." "
                    . "id='".$frm['id']."' "
                    . "".($frm['required'] ? "required" : "")." "
                    . "".($frm['name'] != null ? ""
                    . "name='".$frm['name']."'" : "")." "
                    . "".($frm['placeholder'] != null ? "placeholder='".$frm['placeholder']."'" : "")
                    ." ".(!$frm['enabled'] ? 'disabled' : '' )." />"
                . "</div>";
        }else if($frm['typeForm'] == 'select'){
            $str = "<div class='form-group'>"
                . "<label for='".($frm['name'] != null ? $frm['name'] : "")."'>".$frm['label'].( $frm['required'] ? " <span id='span_required_".($frm['id'])."' class='text-danger'>*</span>" : "" ) ."</label>";
            
            $str .= "<select "
                    . "class='form-control ".$frm['class']."'"
                    . "id='".$frm['id']."' "
                    . "".($frm['required'] ? "required" : "")." "
                    . "".($frm['name'] != null ? ""
                    . "name='".$frm['name']."'" : "")." "
                    ." ".(!$frm['enabled'] ? 'disabled' : '' )." ".($frm['multiple'] ? "multiple" : "")." >";
            
            $options = $frm['options'];
            $str .= "<option value=''>Selecciona</option>";
            foreach ($options as $key => $item) {
                $selected = $this->esSeleccionado($frm['value'], $item['value']) ? "selected" : "";
                $str .= "<option value='".$item['value']."' $selected>".$item['label']."</option>";
            }
            
            $str .= "</select></div>";
            echo $str;
        }else if($frm['typeForm'] == 'textarea'){
            echo "<div class='form-group'>"
                . "<label for='".($frm['name'] != null ? $frm['name'] : "")."'>".$frm['label'].( $frm['required'] ? " <span id='span_required_".($frm['id'])."' class='text-danger'>*</span>" : "" ) ."</label>"
                . "<textarea "
                    . "class='form-control ".$frm['class']."' "
                    . "id='".$frm['id']."' "
                    . "".($frm['required'] ? "required" : "")." "
                    . "".($frm['name'] != null ? ""
                    . "name='".$frm['name']."'" : "")." "
                    . "".($frm['placeholder'] != null ? "placeholder='".$frm['placeholder']."'" : "placeholder='".$frm['label']."'")
                    ." ".(!$frm['enabled'] ? 'disabled' : '' )." >".($frm['value'] != null ? $frm['value'] : "")."</textarea>"
                . "</div>";
        }
    }
    
    public function createAndRenderBs4FormInput(
            $id,
            $label = "Input",
            $name = null,
            $defValue = null,
            $isRequired = false,
            $type = "text",
            $class = "",
            $placeholder = '',
            $enabled = true
            ){
        $this->bs4FormInput($id, $label, $name, $defValue, $isRequired, $type, $class, $placeholder, $enabled);
        $this->render($id);
    }
    
    public function bs4FormSelect(
            $id,
            $label = "Input",
            $name = null,
            $options = [],
            $defValue = null,
            $isRequired = false,
            $class = "",
            $multiple= false,
            $enabled = true){
        
        
        $this->list[$id] = [
            'typeForm' => 'select',
            'id'=>$id, 
            'name' => $name,
            'label'=>$label,
            'value' => $defValue, 
            'options' => $options,
            'multiple' => $multiple,
            'required' => $isRequired,
            'class' => $class,
            'enabled' => $enabled
            ];
        
    }
    
    public function bs4FormTextArea(
            $id,
            $label = "Input",
            $name = null,
            $defValue = null,
            $isRequired = false,
            $class = "",
            $placeholder = '',
            $enabled = true
            ){
        
        
        $this->list[$id] = [
            'typeForm' => 'textarea',
            'id'=>$id, 
            'name' => $name,
            'label'=>$label,
            'value' => $defValue, 
            'required' => $isRequired,
            'class' => $class,
            'placeholder' => $placeholder,
            'enabled' => $enabled
            ];
        
    }
    
    function addButton($text, $faIcon = null, $type = 'submit', $class = ""){
        echo  "<button type='$type' class='btn btn-success $class'>".($faIcon != null ? "<i class='fa $faIcon'></i>" : "" )."  $text</button>";
    }
    

    private function esSeleccionado($item, $value){
        if(is_array($item)){
            return in_array($value, $item);
        }else{
            return $item == $value;
        }
    }
    
}