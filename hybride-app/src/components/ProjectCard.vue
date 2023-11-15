<template>
    <ion-card :data-pr_id="pr_id">
        <ion-card-header>
            <ion-row>
                <ion-col>
                    <ion-card-title>{{ pr_naam }}</ion-card-title>
                    <ion-card-subtitle id="projectCodeTxt" color="secondary">{{ pr_code }}</ion-card-subtitle>
                </ion-col>
                <ion-col size="1.5">
                    <ion-button id="editButton" size="small" fill="clear" aria-label="manage" @click="openProjectModal">
                        <ion-icon slot="icon-only" size="" :icon="createOutline"></ion-icon>
                    </ion-button>
                </ion-col>
            </ion-row>
        </ion-card-header>

        <ion-card-content id="projectOmschrijvingTxt">{{ pr_omschrijving }}</ion-card-content>

        <ion-row id="cardButtonsRow">
            <ion-button fill="clear" color="tertiary" aria-label="edit" @click="showProjectDetails" href="/tabs/tab4">
                Beheer <ion-icon slot="end" :icon="cogOutline" color="tertiary"></ion-icon>
            </ion-button>
            <ion-button :id="'deleteProjectBtn_' + pr_id" fill="clear" color="danger" aria-label="delete"
                @click="handleDeleteClick">
                Verwijder <ion-icon slot="end" :icon="trashOutline" color="danger"></ion-icon>
            </ion-button>
        </ion-row>

        <ion-alert :translucent="true" :trigger="'deleteProjectBtn_' + pr_id" v-model="deleteAlertVisible"
            header="Project Verwijderen" message="Deze actie kan niet ongedaan worden!" :buttons="alertButtons"></ion-alert>
        <ion-toast :translucent="true" :id="'open-toast_' + pr_id" :message="'Project ' + pr_code + ' verwijderd!'"
            :duration="2000"></ion-toast>
        <ProjectModal :isModalOpen="isModalOpen" :projectDetails="selectedProjectDetails" :title="'Project Aanpassen'"
            :type="'put'" @closeModal="closeModal" @projectenUpdated="refreshProjecten" @projectAdded="openUpdateToast" />
    </ion-card>
</template>
    
<script setup>
import { defineProps, defineEmits, inject, ref } from 'vue';
import { IonCard, IonCardHeader, IonCardTitle, IonCardSubtitle, IonCardContent, IonButton, IonIcon, IonRow, IonCol, IonAlert, IonToast } from '@ionic/vue';
import { cogOutline, createOutline, trashOutline, addOutline, cloudUploadOutline } from 'ionicons/icons';
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
    // console.log('sending pr_id ' + pr_id);
    localStorage.setItem('selectedProjectID', pr_id);
}

const openProjectModal = () => {
    openModal();
};

const openDeleteToast = () => {
    const toastInstance = document.getElementById('open-toast_' + pr_id);
    if (toastInstance) {
        toastInstance.icon = trashOutline;
        toastInstance.message = `Project ${pr_code} succesvol verwijderd!`;
        toastInstance.cssClass = 'deleteToast';
        toastInstance.present();
    }
};

const openUpdateToast = () => {
    const toastInstance = document.getElementById('open-toast_' + pr_id);
    if (toastInstance) {
        toastInstance.icon = cloudUploadOutline;
        toastInstance.message = `Project ${pr_code} succesvol aangepast!`;
        toastInstance.cssClass = 'addToast';
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

/* IOS offsets start */

#editButton.ios {
    margin-top: -12px;
}

#cardButtonsRow.ios {
    padding-left: 6px;
}

#projectCodeTxt.ios {
    padding-left: 1px;
}

#projectOmschrijvingTxt.ios {
    padding-left: 26px;
    padding-bottom: 3px;
}

/* IOS offsets end */

/* MD offsets start */

#editButton {
    margin-top: -5px;
}

#cardButtonsRow {
    padding-left: 4px;
}

#projectCodeTxt {
    padding-left: 0px;
}

#projectOmschrijvingTxt {
    padding-left: 21px;
    padding-bottom: 3px;
}

/* MD offsets end */

.alert-wrapper {
    /* border: #92949c 1px solid; */
    border: 0.55px solid rgba(var(--ion-text-color-rgb, 0, 0, 0), 0.2);
}

.alert-button-role-cancel {
    color: #3dc2ff !important;
}

.alertBtnConfirm {
    color: #eb445a !important;
}

.addToast::part(icon) {
    color: #2fdf75;
}

.deleteToast::part(icon) {
    color: #ff4961;
}
</style>
