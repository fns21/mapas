 <?php
    use MapasCulturais\i;
    $this->import('
    entity-occurrence-list
    entity-field 
    entity-terms
    mc-link
    modal 
');
?>

 <modal title="Criar Evento" classes="create-modal" button-label="Criar Evento" @open="createEntity()" @close="destroyEntity()">
     <template v-if="entity && !entity.id" #default>
         <label><?php i::_e('Crie um evento com informações básicas') ?><br><?php i::_e('e de forma rápida') ?></label>
         <div class="create-modal__fields">
             <entity-field :entity="entity" hide-required label=<?php i::esc_attr_e("Nome ou título") ?> prop="name"></entity-field>
             <entity-terms :entity="entity" :editable="true" :classes="linguagemClasses" taxonomy='linguagem' title="<?php i::esc_attr_e("Linguagem cultural") ?>"></entity-terms>
             <small class="field__error" v-if="linguagemErrors">{{linguagemErrors.join(', ')}}</small>
             <entity-field :entity="entity" hide-required prop="shortDescription" label="<?php i::esc_attr_e("Adicione uma Descrição curta para o Evento") ?>"></entity-field>
             <entity-field :entity="entity" hide-required v-for="field in fields" :prop="field"></entity-field>
             {{entity.id}}
         </div>
     </template>

     <template v-if="entity?.id" #default>
        <div class="">
        <h4><?php i::_e('Evento Criado') ?><br></h4>
        <label><?php i::_e('Você pode completar as informações do seu evento agora ou pode deixar para depois. ');?> </label>

        </div>
        <entity-occurrence-list></entity-occurrence-list>
     </template>
     
     <template #button="modal">
         <slot :modal="modal"></slot>
     </template>

     <template v-if="!entity?.id" #actions="modal">
         <button class="button button--primary" @click="createPublic(modal)"><?php i::_e('Criar e Publicar') ?></button>
         <button class="button button--solid-dark" @click="createDraft(modal)"><?php i::_e('Criar em Rascunho') ?></button>
         <button class="button button--text button--text-del " @click="modal.close()"><?php i::_e('Cancelar') ?></button>
     </template>
    
     <template v-if="entity?.id" #actions="modal">
        <button class="button button--text button--text-del " @click="modal.close()"><?php i::_e('Ver Depois')?></button>
        <mc-link :entity="entity" class="button button--text button--text-del">Acessar</mc-link>
        <mc-link :entity="entity" route='edit' class="button button--text button--text-del">Editar</mc-link>
    </template>
 </modal>