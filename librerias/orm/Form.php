<?php

/**
 * @author Reyes Yam <reyesyamm@gmail.com>
 * @copyright (c) 2018, Reyes Yam
 * @version 1.0
 **/

class Form{
    
    var $list = [];
    
    
    public function __construct() {
        
    }

    public function begin($id, $multipart = false){
        echo "<form id='$id' ".($multipart ? "enctype='multipart/form-data'" : "").">";
    }

    public function end(){
        echo "</form>";
    }

    public function inputHidden(Array $conf){
        if(!isset($conf['id'])){
            throw new Error("Input hidden debe tener un id");
        }
        $id = $conf['id'];
        $name = isset($conf['name']) ? $conf['name'] : $conf['id'];
        $value = isset($conf['value']) ? $conf['value'] : '';
        echo "<input type='hidden' name='$name' value='$value' id='$id' />";
    }

    public function bs4FormSelect( Array $conf, $render = false){

        if(!isset($conf['id'])){
            throw new Error("Input hidden debe tener un id");
        }

        $this->list[$conf['id']] = [
            'typeForm' => 'select',
            'id'=> $conf['id'],
            'name' => isset($conf['name']) ? $conf['name'] : $conf['id'],
            'label'=> isset($conf['label']) ? $conf['label'] : 'Input',
            'value' => isset($conf['value']) ? $conf['value'] : null, 
            'required' => isset($conf['required']) ? $conf['required'] : false,
            'class' => isset($conf['class']) ? $conf['class'] : '',
            'enabled'=> isset($conf['enabled']) ? $conf['enabled'] : true,
            'options' => isset($conf['options']) ? $conf['options'] : [],
            'multiple' => isset($conf['multiple']) ? $conf['multiple'] : false,
            ];

        if($render){
            $this->render($conf['id']);
        }
    }
    
    public function bs4FormTextArea( Array $conf, $render = false){

        if(!isset($conf['id'])){
            throw new Error("Debes indicar al menos un id para el elemento form");
        }

        $this->list[$conf['id']] = [
            'typeForm' => 'textarea',
            'id'=> $conf['id'],
            'name' => isset($conf['name']) ? $conf['name'] : $conf['id'],
            'label'=> isset($conf['label']) ? $conf['label'] : 'Input',
            'value' => isset($conf['value']) ? $conf['value'] : null, 
            'required' => isset($conf['required']) ? $conf['required'] : false,
            'class' => isset($conf['class']) ? $conf['class'] : '',
            'placeholder' => isset($conf['placeholder']) ? $conf['placeholder'] : '',
            'enabled'=> isset($conf['enabled']) ? $conf['enabled'] : true,
            'rows' => isset($conf['rows']) ? $conf['rows'] : 3,
            'maxlength' => isset($conf['maxlength']) ? $conf['maxlength'] : null,
            ];

        if($render){
            $this->render($conf['id']);
        }
    }

    public function bs4FormInput(Array $conf, $render = false){
        if(!isset($conf['id'])){
            throw new Error("Debes indicar al menos un id para el elemento form");
        }
        
        $this->list[$conf['id']] = [
            'typeForm' => 'input',
            'id'=> $conf['id'],
            'name' => isset($conf['name']) ? $conf['name'] : $conf['id'],
            'label'=> isset($conf['label']) ? $conf['label'] : 'Input',
            'value' => isset($conf['value']) ? $conf['value'] : null, 
            'required' => isset($conf['required']) ? $conf['required'] : false,
            'type' => isset($conf['type']) ? $conf['type'] : 'text',
            'class' => isset($conf['class']) ? $conf['class'] : '',
            'placeholder' => isset($conf['placeholder']) ? $conf['placeholder'] : '',
            'enabled'=> isset($conf['enabled']) ? $conf['enabled'] : true,
            'min'=> isset($conf['min']) ? $conf['min'] : null,
            'max'=> isset($conf['max']) ? $conf['max'] : null,
            'maxlength' => isset($conf['maxlength']) ? $conf['maxlength'] : null,
            'step' => isset($conf['step']) ? $conf['step'] : null,
            ];

        if($render){
            $this->render($conf['id']);
        }
    }
    
    public function render($id){
        $frm = $this->list[$id];
        if($frm['typeForm'] == 'input'){
            $tmp1 = "";
            if(isset($frm['maxlength']) && $frm['maxlength'] != null){
                $tmp1.=" maxlength='".$frm['maxlength']."' ";
            }

            if(isset($frm['min']) && $frm['min'] != null){
                $tmp1.=" min='".$frm['min']."' ";
            }

            if(isset($frm['max']) && $frm['max'] != null){
                $tmp1.=" min='".$frm['max']."' ";
            }

            if(isset($frm['step']) && $frm['step'] != null){
                $tmp1.=" step='".$frm['step']."' ";
            }

            echo "<div class='form-group'>"
                . "<label for='".($frm['name'] != null ? $frm['name'] : "")."'>".$frm['label'].( $frm['required'] ? " <span class='text-danger'>*</span>" : "" ) ."</label>"
                . "<input "
                    . "type='".$frm['type']."' $tmp1 "
                    . "class='form-control ".$frm['class']."' "
                    . ($frm['value'] != null ? " value='".$frm['value']."' " : "")." "
                    . "id='".$frm['id']."' "
                    . "".($frm['required'] ? "required" : "")." "
                    . "".($frm['name'] != null ? ""
                    . "name='".$frm['name']."'" : "")." "
                    . "".($frm['placeholder'] != null ? "placeholder='".$frm['placeholder']."'" : "placeholder='".$frm['label']."'")
                    ." ".(!$frm['enabled'] ? 'disabled' : '' )." />"
                . "</div>";
        }else if($frm['typeForm'] == 'select'){
            $str = "<div class='form-group'>"
                . "<label id='label_for_".$frm['id']."' for='".($frm['name'] != null ? $frm['name'] : "")."'>".$frm['label'].( $frm['required'] ? " <span class='text-danger'>*</span>" : "" ) ."</label>";
            
            

            $str .= "<select "
                    . "class='form-control ".$frm['class']."'"
                    . "id='".$frm['id']."'"
                    . "".($frm['required'] ? "required" : "")." "
                    . "".($frm['name'] != null ? ""
                    . "name='".$frm['name']."'" : "")." "
                    ." ".(!$frm['enabled'] ? 'disabled' : '' )." ".($frm['multiple'] ? "multiple" : "")." >";
            
            $options = $frm['options'];
            $str .= $frm['multiple'] ? '' : "<option value=''>Selecciona</option>";
            foreach ($options as $key => $item) {
                $selected = $this->esSeleccionado($frm['value'], $key) ? "selected" : "";
                $str .= "<option value='".$key."' $selected>".$item."</option>";
            }
            
            $str .= "</select></div>";
            echo $str;
        }else if($frm['typeForm'] == 'textarea'){

            $tmp1 = "";
            if(isset($frm['maxlength']) && $frm['maxlength'] != null){
                $tmp1.=" maxlength='".$frm['maxlength']."' ";
            }

            echo "<div class='form-group'>"
                . "<label for='".($frm['name'] != null ? $frm['name'] : "")."'>".$frm['label'].( $frm['required'] ? " <span class='text-danger'>*</span>" : "" ) ."</label>"
                . "<textarea "
                    . "class='form-control ".$frm['class']."' "
                    . "id='".$frm['id']."' $tmp1 "
                    . "".($frm['required'] ? "required" : "")." "
                    . "".($frm['name'] != null ? ""
                    . "name='".$frm['name']."'" : "")." "
                    . "rows='".$frm['rows']."'"." "
                    . "".($frm['placeholder'] != null ? "placeholder='".$frm['placeholder']."'" : "")
                    ." ".(!$frm['enabled'] ? 'disabled' : '' )." >".($frm['value'] != null ? $frm['value'] : "")."</textarea>"
                . "</div>";
        }
    }
    
    function renderButton($text, $faIcon = null, $type = 'submit', $class = "" ){
        echo  "<button type='$type' class='btn btn-success $class'>".($faIcon != null ? "<i class='fa $faIcon'></i>" : "" )."  $text</button>";
    }

    private function esSeleccionado($item, $value){
        if($item == "" || $item == null){
            return false;
        }

        if(is_array($item)){
            return in_array($value, $item);
        }else{
            return $item."" == $value."";
        }
    }
    
}