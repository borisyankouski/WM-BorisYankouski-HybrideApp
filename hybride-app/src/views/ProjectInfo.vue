<template>
    <ion-page>
        <ion-header :translucent="true">
            <ion-toolbar>
                <ion-row id="topToolbarRow">
                    <ion-col size="1.5">
                        <ion-button id="backButton" fill="clear" color="primary" href="tabs/tab2">
                            <ion-icon size="small" slot="icon-only" :icon="chevronBackOutline"></ion-icon>
                        </ion-button>
                    </ion-col>

                    <ion-col id="selectCol">
                        <ion-select v-model="selectedProjectID" :interface-options="{ translucent: true, header: 'Kies project' }" aria-label="Project Code" interface="action-sheet"
                            label-placement="floating" @ionChange="selectionChanged">
                            <ion-select-option v-for="project in projecten" :value="project.pr_id">{{ project.pr_naam
                            }}</ion-select-option>
                        </ion-select>
                    </ion-col>
                </ion-row>
            </ion-toolbar>
            <ion-toolbar>
                <ion-searchbar @ionInput="handleSearch" placeholder=" Zoeken"></ion-searchbar>
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
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonList, onIonViewWillEnter, IonSelect, IonSelectOption, IonSearchbar, IonButton, IonBackButton, IonIcon, IonRow, IonCol } from '@ionic/vue';
import { ref, inject } from 'vue';
import ProjectMedewerkerItem from '@/components/ProjectMedewerkerItem.vue';
import { chevronBackOutline } from 'ionicons/icons';

const allMedewerkers = ref([]);
const medewerkers = ref([]);

const axios = inject('axios');

const selectedProjectID = ref(null);

const projecten = ref([]);
let activeMedewerkerIDs = [];

const getProjectCode = () => {
    const selectedProject = projecten.value.find(project => project.pr_id == selectedProjectID.value);
    return selectedProject ? selectedProject.pr_code : '';
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

const getActiveMedewerkerIDs = async () => {
    try {
        const response = await axios.get('https://www.kovskib.com/MW/RESTfulAPI/api/PROJECTMEDEWERKERS.php', {
            params: {
                pr_id: selectedProjectID.value
            }
        });

        console.log(response.data.data);

        if (response.status !== 200) {
            console.log('Error tijdens het ophalen van medewerkers' + response.status);
            return;
        }

        if (!response.data.data) {
            console.log('response.data.data is niet OK');
            return;
        }
        activeMedewerkerIDs = response.data.data.map(item => item.mw_id);
    } catch (error) {
        console.error('Error tijdens het ophalen van medewerkers', error);
    }
};

const getMedewerkers = async () => {
    try {
        const response = await axios.get('https://www.kovskib.com/MW/RESTfulAPI/api/MEDEWERKERS.php');

        console.log(response.data.data);

        if (response.status !== 200) {
            console.log('Error tijdens het ophalen van medewerkers' + response.status);
            return;
        }

        if (!response.data.data) {
            console.log('response.data.data is niet OK');
            return;
        }

        medewerkers.value = response.data.data.map((item, index) => ({
            mw_id: item.mw_id,
            mw_naam: item.mw_voornaam + ' ' + item.mw_familienaam,
            sp_naam: item.sp_naam,
            listIndex: index + 1,
            isActive: activeMedewerkerIDs.includes(item.mw_id)
        }));

        allMedewerkers.value = medewerkers.value;
    } catch (error) {
        console.error('Error tijdens het ophalen van medewerkers', error);
    }
};

const handleSearch = (event) => {
    const searchTerm = event.target.value.toLowerCase();
    if (searchTerm == '') {
        medewerkers.value = allMedewerkers.value;
    } else {
        medewerkers.value = allMedewerkers.value.filter((medewerker) => {
            const fullName = medewerker.mw_naam.toLowerCase();
            return fullName.includes(searchTerm);
        });
    }
};

const refreshMedewerkers = async () => {
    activeMedewerkerIDs = [];
    await getActiveMedewerkerIDs();
    medewerkers.value = [];
    await getMedewerkers();
};

const selectionChanged = () => {
    refreshMedewerkers();
};

onIonViewWillEnter(() => {
    selectedProjectID.value = parseInt(localStorage.getItem('selectedProjectID'));
    getProjecten();
    refreshMedewerkers();
});
</script>

<style scoped>
#topToolbarRow {
    max-height: 50px;
    margin-top: -10px;
}

#selectCol.ios {
    padding-right: 12px;
}

#selectCol {
    padding-right: 7px;
}

#backButton.ios {
    margin-left: -10px;
    margin-top: 0px;
}

#backButton {
    margin-left: -10px;
    margin-top: 10px;
}

ion-searchbar.ios {
    padding-top: 3px;
}

ion-searchbar {
    padding-top: 0px;
}
</style>
  