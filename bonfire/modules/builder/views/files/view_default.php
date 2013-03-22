<?php

$view = '
<?php if (validation_errors()) : ?>
<div class="alert alert-block alert-error fade in ">
  <a class="close" data-dismiss="alert">&times;</a>
  <h4 class="alert-heading">Please fix the following errors :</h4>
 <?php echo validation_errors(); ?>
</div>
<?php endif; ?>
<?php // Change the css classes to suit your needs
if( isset($'.$module_name_lower.') ) {
    $'.$module_name_lower.' = (array)$'.$module_name_lower.';
}
$id = isset($'.$module_name_lower.'[\''.$primary_key_field.'\']) ? $'.$module_name_lower.'[\''.$primary_key_field.'\'] : \'\';
';
$view .= '?>';
$view .= '
<div class="admin-box">
    <h3>' . $module_name . '</h3>
<?php echo form_open($this->uri->uri_string(), \'class="form-horizontal"\'); ?>
    <fieldset>
';
$on_click = '';
$xinha_names = '';

$view .= '
        <?php include("_form.php"); ?>
';

if (!empty($on_click))
{
    $on_click .= '"';
}//end if

$delete = '';

if($action_name != 'create') {
    $delete_permission = preg_replace("/[ -]/", "_", ucfirst($module_name)).'.'.ucfirst($controller_name).'.Delete';

    $delete = PHP_EOL . '
    <?php if ($this->auth->has_permission(\''.$delete_permission.'\')) : ?>

            or <button type="submit" name="delete" class="btn btn-danger" id="delete-me" onclick="return confirm(\'<?php e(js_escape(lang(\''.$module_name_lower.'_delete_confirm\'))); ?>\')">
            <i class="icon-trash icon-white">&nbsp;</i>&nbsp;<?php echo lang(\''.$module_name_lower.'_delete_record\'); ?>
            </button>

    <?php endif; ?>
' . PHP_EOL;
}

$view .= PHP_EOL . '

        <div class="form-actions">
            <br/>
            <input type="submit" name="save" class="btn btn-primary" value="<?php echo lang(\''.$module_name_lower.'_action_'.$action_name.'\'); ?>"'.$on_click.' />
            or <?php echo anchor(SITE_AREA .\'/'.$controller_name.'/'.$module_name_lower.'\', lang(\''.$module_name_lower.'_cancel\'), \'class="btn btn-warning"\'); ?>
            ' . $delete . '
        </div>
    </fieldset>
    <?php echo form_close(); ?>
' . PHP_EOL;



if ($xinha_names != '')
{
    $view .= PHP_EOL . '
                <script type="text/javascript">

                var xinha_plugins =
                [
                 \'Linker\'
                ];
                var xinha_editors =
                [
                  '.$xinha_names.'
                ];

                function xinha_init()
                {
                  if(!Xinha.loadPlugins(xinha_plugins, xinha_init)) return;

                  var xinha_config = new Xinha.Config();

                  xinha_editors = Xinha.makeEditors(xinha_editors, xinha_config, xinha_plugins);

                  Xinha.startEditors(xinha_editors);
                }
                xinha_init();
                </script>' . PHP_EOL;
}

$view .= PHP_EOL . '</div>' . PHP_EOL;
echo $view;
?>