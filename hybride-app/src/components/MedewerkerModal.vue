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
        <ion-content class="ion-padding">
            <ion-item>
                <ion-input v-model="medewerkerVoornaam" label-placement="floating" placeholder="Jans">
                    <div slot="label">Voornaam <ion-text color="danger">(Verplicht)</ion-text></div>
                </ion-input>
            </ion-item>

            <ion-item>
                <ion-input v-model="medewerkerFamilienaam" label-placement="floating" placeholder="Janssens">
                    <div slot="label">Familienaam <ion-text color="danger">(Verplicht)</ion-text></div>
                </ion-input>
            </ion-item>

            <ion-item>
                <ion-select v-model="specialisatieID" label-placement="floating" interface="action-sheet">
                    <div slot="label">Specialisatie <ion-text color="danger">(Verplicht)</ion-text></div>
                    <ion-select-option v-for="specialisatie in specialisaties" :value="specialisatie.sp_id">{{
                        specialisatie.sp_naam
                    }}</ion-select-option>
                </ion-select>
            </ion-item>

            <ion-button style="margin-top: 26px;" @click="submitForm" expand="full" color="success">Submit</ion-button>
        </ion-content>
    </ion-modal>
</template>
  
<script setup>
import { ref, inject, onMounted, watch, defineProps, defineEmits } from 'vue';
import { IonModal, IonHeader, IonToolbar, IonTitle, IonButtons, IonButton, IonIcon, IonContent, IonItem, IonLabel, IonInput, IonText, IonTextarea, IonRow, IonCol, onIonViewDidEnter, IonSelect, IonSelectOption } from '@ionic/vue';
import { closeCircleOutline } from 'ionicons/icons';

const { isModalOpen, medewerkerDetails, title, type } = defineProps(['isModalOpen', 'medewerkerDetails', 'title', 'type']);
const emit = defineEmits();

const medewerkerID = ref('');
const medewerkerVoornaam = ref('');
const medewerkerFamilienaam = ref('');
const specialisatieID = ref('');

const clearFields = () => {
    medewerkerID.value = '';
    medewerkerVoornaam.value = '';
    medewerkerFamilienaam.value = '';
    specialisatieID.value = '';
}

const updateValues = () => {
    medewerkerID.value = medewerkerDetails ? medewerkerDetails.mw_id : '';
    medewerkerVoornaam.value = medewerkerDetails ? medewerkerDetails.mw_voornaam : '';
    medewerkerFamilienaam.value = medewerkerDetails ? medewerkerDetails.mw_familienaam : '';
    specialisatieID.value = medewerkerDetails ? medewerkerDetails.sp_id : '';
};

onMounted(() => {
    getSpecialisaties();
    updateValues();
});

const closeModal = () => {
    emit('closeModal');
    if (type == 'post') {
        clearFields();
    }
};

const submitForm = () => {
    if (medewerkerVoornaam.value.length < 1 || medewerkerFamilienaam.value.length < 1 || specialisatieID.value < 1) {
        console.log('formchecking failed');
        return;
    }
    if (type == 'post') {
        postMedewerker({ mw_voornaam: medewerkerVoornaam.value, mw_familienaam: medewerkerFamilienaam.value, mw_specialisatie_id: parseInt(specialisatieID.value) });
    }
    else if (type == 'put') {
        putMedewerker({ mw_id: medewerkerID.value, mw_voornaam: medewerkerVoornaam.value, mw_familienaam: medewerkerFamilienaam.value, mw_specialisatie_id: parseInt(specialisatieID.value) });
    }
    closeModal();
};

const axios = inject('axios');
const postMedewerker = (medewerker) => {
    console.log(medewerker);
    axios
        .post('https://www.kovskib.com/MW/RESTfulAPI/api/MEDEWERKERS.php', medewerker)
        .then(response => {
            if (response.status !== 200) {
                console.log('Error tijdens het posten van medewerker' + response.status)
            }
            console.log('Medewerker added');
            emit('medewerkerAdded');
            emit('medewerkersUpdated');
        });
}

const putMedewerker = (medewerker) => {
    console.log(medewerker);
    axios
        .put('https://www.kovskib.com/MW/RESTfulAPI/api/MEDEWERKERS.php', medewerker)
        .then(response => {
            if (response.status !== 200) {
                console.log('Error tijdens het updaten van medewerker' + response.status)
            }
            console.log('Medewerker updated');
            emit('medewerkerAdded');
            emit('medewerkersUpdated');
        });
}

const specialisaties = ref([]);
const getSpecialisaties = () => {
    axios
        .get('https://www.kovskib.com/MW/RESTfulAPI/api/SPECIALISATIES.php')
        .then(response => {
            if (response.status !== 200) {
                console.log('Error tijdens het ophalen van specialisatie' + response.status)
            }
            if (!response.data.data) {
                console.log('response.data.data is niet OK');
                return;
            }
            console.log(response.data);
            specialisaties.value = response.data.data;
        });
}
</script>