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
            <ion-button :id="'deleteProjectBtn_' + pr_id" fill="clear" color="danger" aria-label="delete"
                @click="handleDeleteClick">
                Verwijder <ion-icon slot="end" :icon="trashOutline" color="danger"></ion-icon>
            </ion-button>
        </ion-row>
        <ion-alert :trigger="'deleteProjectBtn_' + pr_id" v-model="deleteAlertVisible" header="Project Verwijderen"
            message="Deze actie kan niet ongedaan worden!" :buttons="alertButtons"></ion-alert>
        <ion-toast :id="'open-toast_' + pr_id" :message="'Project ' + pr_code + ' succesvol verwijderd!'" :duration="3000"></ion-toast>
    </ion-card>
</template>
    
<script setup>
import { defineProps, defineEmits, inject, ref } from 'vue';
import { IonCard, IonCardHeader, IonCardTitle, IonCardSubtitle, IonCardContent, IonButton, IonIcon, IonRow, IonAlert, IonToast } from '@ionic/vue';
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

const openToast = () => {
    const toastInstance = document.getElementById('open-toast_' + pr_id);
    console.log(toastInstance);
    if (toastInstance) {
        toastInstance.present();
    }
};

const deleteAlertVisible = ref(false);

const showDeleteAlert = () => {
    console.log('Showing delete alert');
    deleteAlertVisible.value = true;
};

const hideDeleteAlert = () => {
    deleteAlertVisible.value = false;
};

const handleDeleteClick = () => {
    showDeleteAlert();
};

const alertButtons = [
    {
        text: 'Annuleren',
        role: 'cancel',
        color: 'tertiary',
        handler: () => {
            hideDeleteAlert();
        },
    },
    {
        text: 'Verwijderen',
        cssClass: 'alertBtnConfirm',
        // id: 'open-toast_'+pr_id,
        handler: () => {
            deleteProject(pr_id);
            hideDeleteAlert();
        },
    },
];

const axios = inject('axios');
const deleteProject = (projectID) => {
    axios
        .delete('https://www.kovskib.com/MW/RESTfulAPI/api/PROJECTEN.php', {
            data: { pr_id: projectID }
        })
        .then(response => {
            if (response.status !== 200) {
                console.log('Error tijdens het verwijderen van project' + response.status)
            }
            console.log('Project removed');
            emit('projectenUpdated');
            openToast();
            // showToast(`Project ${pr_code} verwijderd!`);
        });
}
</script>
  
<style>
#btnDelete button {
    align-self: flex-end;
}

.alert-wrapper {
    border: #f4f5f8 1px solid;
}

.alert-button-role-cancel {
    color: #3dc2ff !important;
}

.alertBtnConfirm {
    color: #eb445a !important;
}
</style>
