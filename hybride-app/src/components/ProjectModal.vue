<template>
    <ion-modal id="modalWrapper" :is-open="isModalOpen" @ionModalDidDismiss="closeModal" auto-height>
        <ion-header>
            <ion-toolbar>
                <ion-title style="align-self: flex-start;">{{ title }}</ion-title>
                <ion-buttons slot="end">
                    <ion-button @click="closeModal">
                        <ion-icon :icon="closeCircleOutline" color="danger"></ion-icon>
                    </ion-button>
                </ion-buttons>
            </ion-toolbar>
        </ion-header>
        <ion-content style="margin-top: auto; margin-bottom: auto;">
            <ion-item>
                <ion-input v-model="projectNaam" label-placement="floating" placeholder="Alphabet Project">
                    <div slot="label">Naam <ion-text color="danger">(Verplicht)</ion-text></div>
                </ion-input>
            </ion-item>

            <ion-item>
                <ion-input v-model="projectCode" label-placement="floating" placeholder="AB1234">
                    <div slot="label">Code <ion-text color="danger">(Verplicht)</ion-text></div>
                </ion-input>
            </ion-item>

            <ion-item style="min-height: 100px;">
                <ion-label position="floating">Omschrijving</ion-label>
                <ion-textarea style="min-height: 100px;" v-model="projectOmschrijving"
                    placeholder="Het Alphabet Project is een ambitieus initiatief gericht op het verbeteren van alfabetiseringsniveaus en taalvaardigheden in lokale gemeenschappen."></ion-textarea>
            </ion-item>
            <ion-button style="margin-top: 26px;" @click="submitForm" expand="full" color="success">Submit</ion-button>
        </ion-content>
    </ion-modal>
</template>
  
<script setup>
import { ref, inject, onMounted, watch, defineProps, defineEmits } from 'vue';
import { IonModal, IonHeader, IonToolbar, IonTitle, IonButtons, IonButton, IonIcon, IonContent, IonItem, IonLabel, IonInput, IonText, IonTextarea, IonRow, IonCol, onIonViewDidEnter } from '@ionic/vue';
import { closeCircleOutline } from 'ionicons/icons';

const { isModalOpen, projectDetails, title, type } = defineProps(['isModalOpen', 'projectDetails', 'title', 'type']);
const emit = defineEmits();

const projectID = ref('');
const projectNaam = ref('');
const projectCode = ref('');
const projectOmschrijving = ref('');

const clearFields = () => {
    projectID.value = '';
    projectNaam.value = '';
    projectCode.value = '';
    projectOmschrijving.value = '';
}

const updateValues = () => {
    console.log(`test ${projectDetails}`);
    console.log(projectDetails);
    projectID.value = projectDetails ? projectDetails.pr_id : '';
    projectNaam.value = projectDetails ? projectDetails.pr_naam : '';
    projectCode.value = projectDetails ? projectDetails.pr_code : '';
    projectOmschrijving.value = projectDetails ? projectDetails.pr_omschrijving : '';
};

onMounted(() => {
    updateValues();
});

const closeModal = () => {
    emit('closeModal');
};

const submitForm = () => {
    if (projectNaam.value.length < 1 || projectCode.value.length < 1) {
        console.log('formchecking failed');
        return;
    }
    if (type == 'post') {
        postProject({pr_naam: projectNaam.value, pr_code: projectCode.value, pr_omschrijving: projectOmschrijving.value});
    }
    else if (type == 'put') {
        putProject({pr_id: projectID.value, pr_naam: projectNaam.value, pr_code: projectCode.value, pr_omschrijving: projectOmschrijving.value});   
    }
    clearFields();
    closeModal();
};

const axios = inject('axios');
const postProject = (project) => {
  axios
    .post('https://www.kovskib.com/MW/RESTfulAPI/api/PROJECTEN.php', project)
    .then(response => {
      if (response.status !== 200) {
        console.log('Error tijdens het posten van medewerker' + response.status)
      }
      console.log('Project added');
      emit('projectAdded');
      emit('projectenUpdated');
    });
}

const putProject = (project) => {
  axios
    .put('https://www.kovskib.com/MW/RESTfulAPI/api/PROJECTEN.php', project)
    .then(response => {
      if (response.status !== 200) {
        console.log('Error tijdens het updaten van' + response.status)
      }
      console.log('Project updated');
      emit('projectAdded');
      emit('projectenUpdated');
    });
}
</script>