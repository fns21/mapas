<?php
$action = preg_replace("#^(\w+/)#", "", $this->template);

$this->bodyProperties['ng-app'] = "entity.app";
$this->bodyProperties['ng-controller'] = "EntityController";

$this->jsObject['angularAppDependencies'][] = 'entity.module.opportunity';

$this->addEntityToJs($entity);

$this->addOpportunityToJs($entity->opportunity);
// remover os que nao tem permissao de leitura


$this->addOpportunitySelectFieldsToJs($entity->opportunity);

$this->addRegistrationToJs($entity);
$this->includeAngularEntityAssets($entity);
$this->includeEditableEntityAssets();

// Verify allowed fields
$fields = $this->jsObject['entity']['registrationFieldConfigurations'];
foreach ($fields as $key => $f) {
    $name=$f->fieldName;
    if( !isset($userAllowedFields->$name)){
        unset($fields[$key]);
    }
}
$this->jsObject['entity']['registrationFieldConfigurations'] = array_values($fields);
$this->jsObject['userAllowedFields'] = $userAllowedFields;

// Verify allowed files
$files = $this->jsObject['entity']['registrationFileConfigurations'];
foreach ($files as $key => $f) {
    $name=$f->getFileGroupName();
    if( !isset($userAllowedFields->$name)){
        unset($files[$key]);
    }
}
$this->jsObject['entity']['registrationFileConfigurations'] = array_values($files);

$_params = [
    'entity' => $entity,
    'action' => $action,
    'opportunity' => $entity->opportunity
];
?>

<article class="main-content registration" ng-controller="OpportunityController">
    <?php $this->part('singles/registration--header', $_params); ?>

    <article ng-controller="SupportForm">
        <?php $this->applyTemplateHook('form','begin'); ?>
        <div ng-if="data.fields.length > 0" id="registration-attachments" class="registration-fieldset">
            <!--
            <h4><?php \MapasCulturais\i::_e("Campos adicionais do formulário de inscrição.");?></h4>
            -->
            <?php $this->applyTemplateHook('registration-field-list', 'before') ?>
        
            <ul class="attachment-list" ng-controller="RegistrationFieldsController">
                <?php $this->applyTemplateHook('registration-field-list', 'begin') ?>
                <li ng-repeat="field in data.fields" ng-if="showField(field)" id="field_{{::field.id}}" data-field-id="{{::field.id}}" ng-class=" (field.fieldType != 'section') ? 'js-field attachment-list-item registration-view-mode' : ''">
                    <div ng-if="canUserEdit(field)">
                        <?php $this->part('singles/registration-field-edit') ?>
                    </div>
                    <div ng-if="!canUserEdit(field)" >
                        <?php $this->part('singles/registration-field-view') ?>
                    </div>
                </li>
                <?php $this->applyTemplateHook('registration-field-list', 'end') ?>
            </ul>
            <?php $this->applyTemplateHook('registration-field-list', 'after') ?>
        </div>

        <?php $this->applyTemplateHook('form','end'); ?>
    </article>
</article>
