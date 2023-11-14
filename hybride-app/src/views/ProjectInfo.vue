<template>
    <ion-page>
        <ion-header>
            <ion-toolbar>
                <ion-select v-model="selectedProjectID" aria-label="Project Code" interface="action-sheet"
                    label-placement="floating" @ionChange="selectionChanged">
                    <ion-select-option v-for="project in projecten" :value="project.pr_id">{{ project.pr_naam
                    }}</ion-select-option>
                </ion-select>
            </ion-toolbar>
        </ion-header>
        <ion-content :fullscreen="true">
            <ion-header collapse="condense">
                <ion-toolbar>
                    <ion-title size="large">{{ getProjectCode() }}</ion-title>
                </ion-toolbar>
            </ion-header>
            <ion-list lines="full">
                <ProjectMedewerkerItem v-for="medewerker in medewerkers" :key="medewerker.mw_id"
                    :mw_naam="medewerker.mw_naam" :sp_naam="medewerker.sp_naam" :mw_id="medewerker.mw_id"
                    :pr_id="selectedProjectID" :listIndex="medewerker.listIndex" :isActive="medewerker.isActive" />
            </ion-list>
        </ion-content>
    </ion-page>
</template>
  
<script setup>
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonList, onIonViewWillEnter, IonSelect, IonSelectOption } from '@ionic/vue';
import { ref, inject } from 'vue';
import eventBus from '@/event-bus.js';
import ProjectMedewerkerItem from '@/components/ProjectMedewerkerItem.vue';

const medewerkers = ref([]);

const axios = inject('axios');

const selectedProjectID = ref(1);
const projecten = ref([]);
let activeMedewerkerIDs = [];

const getProjectCode = () => {
    const selectedProject = projecten.value.find(project => project.pr_id === selectedProjectID.value);
    return selectedProject ? selectedProject.pr_code : ''; // Assuming pr_code is the property you want to display
};

const getActiveMedewerkerIDs = () => {
    console.log(selectedProjectID.value);
    axios
        .get('https://www.kovskib.com/MW/RESTfulAPI/api/PROJECTMEDEWERKERS.php', {
            params: {
                pr_id: selectedProjectID.value
            }
        })
        .then(response => {
            console.log(response.data.data);
            if (response.status !== 200) {
                console.log('Error tijdens het ophalen van medewerkers' + response.status)
            }
            if (!response.data.data) {
                console.log('response.data.data is niet OK');
                return;
            }
            console.log(response.data);
            for (let i = 0, end = response.data.data.length; i < end; i++) {
                activeMedewerkerIDs.push(response.data.data[i].mw_id);
            }
            console.log(activeMedewerkerIDs);
        });
};

const getMedewerkers = () => {
    axios
        .get('https://www.kovskib.com/MW/RESTfulAPI/api/MEDEWERKERS.php')
        .then(response => {
            console.log(response.data.data);
            if (response.status !== 200) {
                console.log('Error tijdens het ophalen van medewerkers' + response.status)
            }
            if (!response.data.data) {
                console.log('response.data.data is niet OK');
                return;
            }
            console.log(response.data);
            for (let i = 0, end = response.data.data.length; i < end; i++) {
                const medewerker = {
                    mw_id: response.data.data[i].mw_id,
                    mw_naam: response.data.data[i].mw_voornaam + ' ' + response.data.data[i].mw_familienaam,
                    sp_naam: response.data.data[i].sp_naam,
                    listIndex: i + 1,
                    isActive: activeMedewerkerIDs.includes(response.data.data[i].mw_id)
                }
                medewerkers.value.push(medewerker);
            }
            console.log(medewerkers);
        });
};

const getProjecten = () => {
    axios
        .get('https://www.kovskib.com/MW/RESTfulAPI/api/PROJECTEN.php')
        .then(response => {
            if (response.status !== 200) {
                console.log('Error tijdens het ophalen van projecten' + response.status)
            }
            if (!response.data.data) {
                console.log('response.data.data is niet OK');
                return;
            }
            console.log(response.data.data);
            for (let i = 0, end = response.data.data.length; i < end; i++) {
                projecten.value.push(response.data.data[i]);
            }
        });
};

const selectionChanged = () => {
    refreshMedewerkers();
};

const refreshMedewerkers = () => {
    activeMedewerkerIDs = [];
    getActiveMedewerkerIDs();
    medewerkers.value = [];
    getMedewerkers();
};

onIonViewWillEnter(() => {
    getProjecten();
    refreshMedewerkers();
    // getActiveMedewerkerIDs();
    // getMedewerkers();
});

eventBus.on('updateSelectedProjectID', (newProjectID) => {
    // Handle the newProjectID here
    console.log('Received updated project ID:', newProjectID);
    selectedProjectID.value = newProjectID;
    refreshMedewerkers();
});
</script>
  