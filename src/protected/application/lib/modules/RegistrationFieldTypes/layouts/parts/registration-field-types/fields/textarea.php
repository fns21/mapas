<?php use MapasCulturais\i; ?>
<textarea ng-required="field.required" ng-model="entity[fieldName]" ng-blur="saveField(field, entity[fieldName])"  maxlength='{{ !field.maxSize ?'': field.maxSize }}'></textarea>
<div ng-if="field.maxSize">
    <?php i::_e('Número de caracteres') ?>:
    {{entity[fieldName].length}} / {{field.maxSize}}
</div>