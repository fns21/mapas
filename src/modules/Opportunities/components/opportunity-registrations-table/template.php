<?php
/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use MapasCulturais\i;

$this->layout = 'entity';

$this->import('
    entity-table
    mc-card
    mc-multiselect
    mc-select
    mc-tag-list
    mc-icon
    v1-embed-tool
');

$entity = $this->controller->requestedEntity;
?>
<div class="opportunity-registration-table grid-12">
    <div class="col-12">
        <h2 v-if="phase.publishedRegistrations"><?= i::__("Os resultados já foram publicados") ?></h2>
        <h2 v-if="!phase.publishedRegistrations && isPast()"><?= i::__("As inscrições já estão encerradas") ?></h2>
        <h2 v-if="isHappening()"><?= i::__("As inscrições estão em andamento") ?></h2>
        <h2 v-if="isFuture()"><?= i::__("As inscrições ainda não iniciaram") ?></h2>
    </div>
    <template v-if="!isFuture()">
        <?php $this->applyTemplateHook('registration-list-actions', 'before', ['entity' => $entity]); ?>
            <div class="col-12 opportunity-registration-table__buttons">
                <?php $this->applyTemplateHook('registration-list-actions', 'begin', ['entity' => $entity]); ?>
               
                <?php $this->applyTemplateHook('registration-list-actions', 'end', ['entity' => $entity]); ?>
            </div>
            <?php $this->applyTemplateHook('registration-list-actions', 'after', ['entity' => $entity]); ?>
            <div class="col-12">
                <h5>
                    <strong><?= i::__("Clique no número de uma inscrição para conferir todas as avaliações realizadas. Após conferir, você pode alterar os status das inscrições de maneira coletiva ou individual e aplicar os resultados das avaliações.") ?></strong>
                    <?= i::__(" Após conferir, você pode alterar os status das inscrições de maneira coletiva ou individual e aplicar os resultados das avaliações.") ?>
                </h5>
            </div>
        <div class="col-12"> 
            <entity-table type="registration" :query="query" :select="select" :headers="headers" phase:="phase" required="number,options" visible="agent,status,category,consolidatedResult" @clear-filters="clearFilters">
                <template #actions="{entities,filters}">
                    <div class="col-4 text-right">
                        <mc-link :entity="phase" route="reportDrafts" class="button button--secondarylight button--md"><label class="down-draft"><?= i::__("Baixar rascunho") ?></label></mc-link>
                    </div>
                    <div class="col-4">
                        <mc-link :entity="phase" route="report" class="button button--secondarylight button--md"><label class="down-list"><?= i::__("Baixar lista de inscrições") ?></label></mc-link>
                    </div>
                </template>
                <template #filters="{entities,filters}">
                    <div class="grid-12">
                        <mc-select class="col-5" :default-value="selectedAvaliation" @change-option="filterAvaliation($event,entities)">
                            <template #empetyOption>
                                <?= i::__("Resultado de avaliação") ?>
                            </template>
                            <option v-for="(item,index) in statusEvaluationResult" :value="index">{{item}}</option>
                        </mc-select>
                        <mc-select class="col-4" :default-value="selectedStatus" @change-option="filterByStatus($event,entities)">
                            <template #empetyOption>
                                <?= i::__("Status de inscrição") ?>
                            </template>
                            <option v-for="item in statusDict" :value="item.value">{{item.label}}</option>
                        </mc-select>
                        <mc-select v-if="statusCategory.length > 0" class="col-3" :default-value="selectedCategory" @change-option="filterByCategory($event,entities)">
                            <template #empetyOption>
                                <?= i::__("Categoria") ?>
                            </template>
                            <option v-for="item in statusCategory" :value="item">{{item}}</option>
                        </mc-select>
                    </div>
                </template>
                <template #status="{entity}">
                  <select v-model="entity.status" @change="alterStatus(entity)">
                        <template v-for="item in statusDict">
                            <option :value="item.value">{{item.label}}</option>
                        </template>
                  </select>
                </template>
                <template #consolidatedResult="{entity}">
                    {{consolidatedResultToString(entity)}}
                </template>
                <template #number="{entity}">
                    <a :href="entity.singleUrl">{{entity.number}}</a>
                </template>
                <template #options="{entity}">
                    <a :href="entity.singleUrl" class="button button--primary"><?= i::__("Conferir inscrição")?></a>
                </template>
            </entity-table>
        </div>
    </template>
</div>