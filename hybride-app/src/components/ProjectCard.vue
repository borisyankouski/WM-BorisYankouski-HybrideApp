<template>
    <ion-card :data-pr_id="pr_id">
        <ion-card-header>
            <ion-row>
                <ion-col>
                    <ion-card-title>{{ pr_naam }}</ion-card-title>
                    <ion-card-subtitle>{{ pr_code }}</ion-card-subtitle>
                </ion-col>
                <ion-col size="2">
                    <ion-button fill="clear" aria-label="manage" @click="openProjectModal">
                        <ion-icon slot="icon-only" size="" :icon="createOutline"></ion-icon>
                    </ion-button>
                </ion-col>
            </ion-row>
        </ion-card-header>

        <ion-card-content>{{ pr_omschrijving }}</ion-card-content>

        <ion-row>
            <ion-button fill="clear" color="tertiary" aria-label="edit" @click="showProjectDetails">
                Beheer <ion-icon slot="end" :icon="cogOutline" color="tertiary"></ion-icon>
            </ion-button>
            <ion-button :id="'deleteProjectBtn_' + pr_id" fill="clear" color="danger" aria-label="delete"
                @click="handleDeleteClick">
                Verwijder <ion-icon slot="end" :icon="trashOutline" color="danger"></ion-icon>
            </ion-button>
        </ion-row>
        <ion-alert :trigger="'deleteProjectBtn_' + pr_id" v-model="deleteAlertVisible" header="Project Verwijderen"
            message="Deze actie kan niet ongedaan worden!" :buttons="alertButtons"></ion-alert>
        <ion-toast :id="'open-toast_' + pr_id" :message="'Project ' + pr_code + ' succesvol verwijderd!'"
            :duration="3000"></ion-toast>
        <ProjectModal :isModalOpen="isModalOpen" :projectDetails="selectedProjectDetails" :title="'Project Aanpassen'"
            :type="'put'" @closeModal="closeModal" @projectenUpdated="refreshProjecten" @projectAdded="openUpdateToast" />
    </ion-card>
</template>
    
<script setup>
import { defineProps, defineEmits, inject, ref } from 'vue';
import { IonCard, IonCardHeader, IonCardTitle, IonCardSubtitle, IonCardContent, IonButton, IonIcon, IonRow, IonCol, IonAlert, IonToast } from '@ionic/vue';
import { cogOutline, createOutline, trashOutline } from 'ionicons/icons';
import ProjectModal from '@/components/ProjectModal.vue';

const { pr_naam, pr_code, pr_omschrijving, pr_id } = defineProps(['pr_naam', 'pr_code', 'pr_omschrijving', 'pr_id']);
const emit = defineEmits();
const isModalOpen = ref(false);

const selectedProjectDetails = ref({
    pr_naam: pr_naam,
    pr_code: pr_code,
    pr_omschrijving: pr_omschrijving,
    pr_id: pr_id,
})

const showProjectDetails = () => {
    
}

const openProjectModal = () => {
    openModal();
};

const openDeleteToast = () => {
    const toastInstance = document.getElementById('open-toast_' + pr_id);
    if (toastInstance) {
        toastInstance.message = `Project ${pr_code} succesvol verwijderd!`;
        toastInstance.present();
    }
};

const openUpdateToast = () => {
    const toastInstance = document.getElementById('open-toast_' + pr_id);
    if (toastInstance) {
        toastInstance.message = `Project ${pr_code} succesvol aangepast!`;
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
            openDeleteToast();
            // showToast(`Project ${pr_code} verwijderd!`);
        });
}

const refreshProjecten = () => {
    emit('projectenUpdated');
};

const openModal = () => {
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};
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
