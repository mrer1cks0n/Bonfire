<?php

$view = '';
$on_click = '';
$xinha_names = '';
for($counter=1; $field_total >= $counter; $counter++)
{
    $maxlength = NULL; // reset this variable

    // only build on fields that have data entered.
    //Due to the requiredif rule if the first field is set the the others must be

    if (set_value("view_field_label$counter") == NULL)
    {
        continue;   // move onto next iteration of the loop
    }

    $field_label = set_value("view_field_label$counter");
    // Edit form breaks when not checking for $table_as_prefix_field
    if ($table_as_field_prefix) {
        $form_name  = $module_name_lower . '_' . set_value("view_field_name$counter");
    } else {
        $form_name  = set_value("view_field_name$counter");
    }
    $field_name = $db_required == 'new' ? $form_name : set_value("view_field_name$counter");
    $field_type = set_value("view_field_type$counter");

    $validation_rules = $this->input->post('validation_rules'.$counter);

    $required = '';
    if (is_array($validation_rules))
    {
        // rules have been selected for this fieldset

        foreach($validation_rules as $key => $value)
        {
            if($value == 'required')
            {
                $required = ". lang('bf_form_label_required')"; //' <span class="required">*</span>';
            }

        }
    }

    // field type
    switch($field_type)
    {

        // Some consideration has gone into how these should be implemented
        // I came to the conclusion that it should just setup a mere framework
        // and leave helpful comments for the developer
        // Modulebuilder is meant to have a minimium amount of features.
        // It sets up the parts of the form that are repitive then gets the hell out
        // of the way.

        // This approach maintains these aims/goals

        case('textarea'):

            if (!empty($textarea_editor) )
            {
                // if a date field hasn't been included already then add in the jquery ui files
                if ($textarea_editor == 'xinha') {
                    //
                    if ($xinha_names != '')
                    {
                        $xinha_names .= ', ';
                    }
                    $xinha_names .= '\''.$field_name.'\'';

                }

            }
            $view .= <<<EOT
        <div class="control-group <?php echo form_error('{$field_name}') ? 'error' : ''; ?>">
            <?php echo form_label('{$field_label}'{$required}, '{$form_name}', array('class' => "control-label") ); ?>
            <div class="controls">
                <?php echo form_textarea( array( 'name' => '{$form_name}', 'id' => '{$form_name}', 'rows' => '5', 'cols' => '80', 'value' => set_value('$form_name', isset(\${$module_name_lower}['{$field_name}']) ? \${$module_name_lower}['{$field_name}'] : '') ) )?>
                <span class="help-inline"><?php echo form_error('{$field_name}'); ?></span>
            </div>

        </div>

EOT;
            break;

        case('radio'):

            $view .= <<<EOT
        <div class="control-group <?php echo form_error('{$field_name}') ? 'error' : ''; ?>">
            <?php echo form_label('{$field_label}'{$required}, '', array('class' => "control-label", 'id'=>"{$form_name}_label") ); ?>
            <div class="controls" aria-labelled-by="{$form_name}_label">
                <label class="radio" for="{$form_name}_option1">
                    <input id="{$form_name}_option1" name="{$form_name}" type="radio" class="" value="option1" <?php echo set_radio('{$form_name}', 'option1', TRUE); ?> />
                    Radio option 1
                </label>
                <label class="radio" for="{$form_name}_option2">
                    <input id="{$form_name}_option2" name="{$form_name}" type="radio" class="" value="option2" <?php echo set_radio('{$form_name}', 'option2'); ?> />
                    Radio option 2
                </label>
                <span class="help-inline"><?php echo form_error('{$field_name}'); ?></span>
            </div>

        </div>

EOT;
            break;

        case('select'):
            // decided to use ci form helper here as I think it makes selects/dropdowns a lot easier
            $select_options = array();
            if (set_value("db_field_length_value$counter") != NULL)
            {
                $select_options = explode(',', set_value("db_field_length_value$counter"));
            }
            $view .= '

        <?php // Change the values in this array to populate your dropdown as required ?>

';
            $view .= '<?php $options = array(';
            foreach( $select_options as $key => $option)
            {
                $view .= '
                '.strip_slashes($option).' => '.strip_slashes($option).',';
            }
            $view .= <<<EOT
); ?>

        <?php echo form_dropdown('{$form_name}', \$options, set_value('{$form_name}', isset(\${$module_name_lower}['{$field_name}']) ? \${$module_name_lower}['{$field_name}'] : ''), '{$field_label}'{$required})?>

EOT;
            break;

        case('checkbox'):

            $view .= <<<EOT
        <div class="control-group <?php echo form_error('{$field_name}') ? 'error' : ''; ?>">
            <?php echo form_label('{$field_label}'{$required}, '{$form_name}', array('class' => "control-label") ); ?>
            <div class="controls">

                <label class="checkbox" for="{$form_name}">
                    <input type="checkbox" id="{$form_name}" name="{$form_name}" value="1" <?php echo (isset(\${$module_name_lower}['{$field_name}']) && \${$module_name_lower}['{$field_name}'] == 1) ? 'checked="checked"' : set_checkbox('{$form_name}', 1); ?>>
                    <span class="help-inline"><?php echo form_error('{$field_name}'); ?></span>
                </label>

            </div>

        </div>

EOT;
            break;

        case('input'):
        case('password'):
        default: // input.. added bit of error detection setting select as default

            if ($field_type == 'input')
            {
                $type = 'text';
            }
            else
            {
                $type = 'password';
            }
            if (set_value("db_field_length_value$counter") != NULL)
            {
                $maxlength = 'maxlength="'.set_value("db_field_length_value$counter").'"';
                if (set_value("db_field_type$counter") == 'DECIMAL' || set_value("db_field_type$counter") == 'FLOAT')   {
                    list($len, $decimal) = explode(",", set_value("db_field_length_value$counter"));
                    $max = $len;
                    if (isset($decimal) && $decimal != 0) {
                        $max = $len + 1;        // Add 1 to allow for the
                    }
                    $maxlength = 'maxlength="'.$max.'"';
                }
            }
            $db_field_type = set_value("db_field_type$counter");

            $view .= <<<EOT
        <div class="control-group <?php echo form_error('{$field_name}') ? 'error' : ''; ?>">
            <?php echo form_label('{$field_label}'{$required}, '{$form_name}', array('class' => "control-label") ); ?>
            <div class="controls">

               <input id="{$form_name}" type="{$type}" name="{$form_name}" {$maxlength} value="<?php echo set_value('{$form_name}', isset(\${$module_name_lower}['{$field_name}']) ? \${$module_name_lower}['{$field_name}'] : ''); ?>"  />
               <span class="help-inline"><?php echo form_error('{$field_name}'); ?></span>
            </div>

        </div>
        
EOT;

            break;

    } // end switch
} // end for loop
echo $view;
?>