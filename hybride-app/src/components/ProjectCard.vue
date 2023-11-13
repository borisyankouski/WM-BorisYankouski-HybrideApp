<template>
    <ion-card :data-pr_id="pr_id">
        <ion-card-header>
            <ion-card-title>{{ pr_naam }}</ion-card-title>
            <ion-card-subtitle>{{ pr_code }}</ion-card-subtitle>
        </ion-card-header>

        <ion-card-content>{{ pr_omschrijving }}</ion-card-content>

        <ion-row>
            <ion-button fill="clear" color="tertiary" aria-label="edit" @click="openProjectModal">
                Beheer <ion-icon slot="end" :icon="cogOutline" color="tertiary"></ion-icon>
            </ion-button>
            <ion-button fill="clear" color="danger" aria-label="delete" @click="deleteProject(pr_id)">
                Verwijder <ion-icon slot="end" :icon="trashOutline" color="danger"></ion-icon>
            </ion-button>
        </ion-row>
    </ion-card>
</template>
    
<script setup>
import { defineProps, defineEmits, inject, ref } from 'vue';
import { IonCard, IonCardHeader, IonCardTitle, IonCardSubtitle, IonCardContent, IonButton, IonIcon, IonRow } from '@ionic/vue';
import { cogOutline, trashOutline } from 'ionicons/icons';

const { pr_naam, pr_code, pr_omschrijving, pr_id } = defineProps(['pr_naam', 'pr_code', 'pr_omschrijving', 'pr_id']);
const emit = defineEmits();

const openProjectModal = () => {
    emit('openProjectModal', {
        pr_naam,
        pr_code,
        pr_omschrijving,
        pr_id,
    });
};

const axios = inject('axios');
const deleteProject = (projectID) => {
  axios
    .delete('https://www.kovskib.com/MW/RESTfulAPI/api/PROJECTEN.php',  {
        data: { pr_id: projectID }
    })
    .then(response => {
      if (response.status !== 200) {
        console.log('Error tijdens het verwijderen van project' + response.status)
      }
      console.log('Project removed');
      emit('projectenUpdated');
    });
}
</script>
  
<style scoped>
#btnDelete button {
    align-self: flex-end;
}
</style>