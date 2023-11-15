<template>
    <ion-card :data-mw_id=mw_id :data-sp_id=sp_id>
        <ion-card-header id="cardHeader">
            <ion-row>
                <ion-col>
                    <ion-card-title>{{ mw_voornaam }} {{ mw_familienaam }}</ion-card-title>
                    <ion-card-subtitle color="secondary">{{ sp_naam }}</ion-card-subtitle>
                </ion-col>
                <ion-col size="1.5"> <!-- TODO update in projectCard.vue -->
                    <ion-button id="editButton" size="small" fill="clear" aria-label="manage" @click="openMedewerkerModal">
                        <ion-icon slot="icon-only" size="" :icon="createOutline"></ion-icon>
                    </ion-button>
                </ion-col>
            </ion-row>
        </ion-card-header>

        <ion-row id="cardButtonsRow">
            <ion-button fill="clear" color="tertiary" aria-label="edit" @click="showMedewerkerDetails" href="/tabs/tab5">
                Beheer <ion-icon slot="end" :icon="cogOutline" color="tertiary"></ion-icon>
            </ion-button>
            <ion-button :id="'deleteMedewerkerBtn_' + mw_id" fill="clear" color="danger" aria-label="delete"
                @click="handleDeleteClick">
                Verwijder <ion-icon slot="end" :icon="trashOutline" color="danger"></ion-icon>
            </ion-button>
        </ion-row>

        <ion-alert :translucent="true" :trigger="'deleteMedewerkerBtn_' + mw_id" v-model="deleteAlertVisible"
            header="Medewerker Verwijderen" message="Deze actie kan niet ongedaan worden!" :buttons="alertButtons"></ion-alert>
        <ion-toast :translucent="true" :id="'open-toast_' + mw_id" :message="'Medewerker ' + mw_id + ' verwijderd!'"
            :duration="2000"></ion-toast>
        <MedewerkerModal :isModalOpen="isModalOpen" :medewerkerDetails="selectedMedewerkerDetails" :title="'Medewerker Aanpassen'"
            :type="'put'" @closeModal="closeModal" @medewerkersUpdated="refreshMedewerkers" @medewerkerAdded="openUpdateToast" />
    </ion-card>
</template>
  
<script setup>
import { defineProps, defineEmits, inject, ref } from 'vue';
import { IonCard, IonCardHeader, IonCardTitle, IonCardSubtitle, IonCardContent, IonButton, IonIcon, IonRow, IonCol, IonAlert, IonToast } from '@ionic/vue';
import { cogOutline, createOutline, trashOutline, addOutline, cloudUploadOutline } from 'ionicons/icons';
import MedewerkerModal from '@/components/MedewerkerModal.vue';

const { mw_voornaam, mw_familienaam, sp_naam, sp_id, mw_id } = defineProps(['mw_voornaam', 'mw_familienaam', 'sp_naam', 'sp_id', 'mw_id']);
const emit = defineEmits();
const isModalOpen = ref(false);

const selectedMedewerkerDetails = ref({
    mw_naam: mw_voornaam + ' ' + mw_familienaam,
    mw_voornaam: mw_voornaam,
    mw_familienaam: mw_familienaam,
    sp_naam: sp_naam,
    sp_id: sp_id,
    mw_id: mw_id
})

const showMedewerkerDetails = () => {
    localStorage.setItem('selectedMedewerkerID', mw_id);
}

const openMedewerkerModal = () => {
    openModal();
};

const openDeleteToast = () => {
    const toastInstance = document.getElementById('open-toast_' + mw_id);
    if (toastInstance) {
        toastInstance.icon = trashOutline;
        toastInstance.message = `${mw_voornaam} ${mw_familienaam} succesvol verwijderd!`;
        toastInstance.cssClass = 'deleteToast';
        toastInstance.present();
    }
};

const openUpdateToast = () => {
    const toastInstance = document.getElementById('open-toast_' + mw_id);
    if (toastInstance) {
        toastInstance.icon = cloudUploadOutline;
        toastInstance.message = `${mw_voornaam} ${mw_familienaam} succesvol aangepast!`;
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
        handler: () => {
            deleteMedewerker(mw_id);
            hideDeleteAlert();
        },
    },
];

const axios = inject('axios');
const deleteMedewerker = (medewerkerID) => {
    axios
        .delete('https://www.kovskib.com/MW/RESTfulAPI/api/MEDEWERKERS.php', {
            data: { mw_id: medewerkerID }
        })
        .then(response => {
            if (response.status !== 200) {
                console.log('Error tijdens het verwijderen van medewerker' + response.status)
            }
            console.log('Medewerker removed');
            emit('medewerkersUpdated');
            openDeleteToast();
        });
}

const refreshMedewerkers = () => {
    emit('medewerkersUpdated');
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

#cardHeader.ios {
    padding-bottom: 0;
}

#editButton.ios {
    margin-top: -12px;
}

#cardButtonsRow.ios {
    padding-left: 6px;
}

/* IOS offsets end */

/* MD offsets start */

#cardHeader {
    padding-bottom: 0;
}

#editButton {
    margin-top: -5px;
}

#cardButtonsRow {
    padding-left: 4px;
}

/* MD offsets end */

.alert-wrapper {
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
