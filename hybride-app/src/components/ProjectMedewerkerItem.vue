<template>
    <ion-item>
        <ion-toggle :enable-on-off-labels="true" color="success" :data-mw_id="mw_id" :checked="isActiveEmployee"
            @ion-change="toggleChanged">
            <ion-label>
                <ion-row>
                    <strong>{{ mw_naam }}</strong>
                </ion-row>
                <ion-row>
                    <ion-note color="secondary">{{ sp_naam }}</ion-note>

                </ion-row>
            </ion-label>
        </ion-toggle>
    </ion-item>
    <ion-toast :translucent="true" :id="'projectmedewerker-add-toast'" cssClass="addToast" :icon="addCircleOutline" :message="'Project succesvol toegevoegd!'" :duration="1000"></ion-toast>
    <ion-toast :translucent="true" :id="'projectmedewerker-remove-toast'" cssClass="deleteToast" :icon="trashOutline" :message="'Project succesvol toegevoegd!'" :duration="1000"></ion-toast>
</template>

<script setup>
import { defineProps, inject, ref } from 'vue';
import { IonItem, IonToggle, IonLabel, IonNote, IonRow, IonToast } from '@ionic/vue';
import { trashOutline, cloudUploadOutline, addCircleOutline, addCircle } from 'ionicons/icons';
const { mw_naam, sp_naam, mw_id, pr_id, listIndex, isActive } = defineProps(['mw_naam', 'sp_naam', 'mw_id', 'pr_id', 'listIndex', 'isActive']);
const isActiveEmployee = ref(isActive);

const toggleChanged = (event) => {
    if (event.detail.checked) {
        addMedewerkerToProject(mw_id, pr_id);
        console.log('Adding medewerker ' + mw_id + ' to project ' + pr_id);
        openToast('post');
    }
    else {
        removeMedewerkerFromProject(mw_id, pr_id);
        console.log('Removing medewerker ' + mw_id + ' from project ' + pr_id);
        openToast('delete');
    }
};

const openToast = (type) => {
    const addToastInstance = document.getElementById('projectmedewerker-add-toast');
    const removeToastInstance = document.getElementById('projectmedewerker-remove-toast');
    if (type == 'post') {
        addToastInstance.message = `${mw_naam} toegevoegd aan project!`;
        addToastInstance.present();
    } else if (type == 'delete') {
        removeToastInstance.message = `${mw_naam} verwijderd van project!`;
        removeToastInstance.present();
    }
};

const axios = inject('axios');
const addMedewerkerToProject = (mw_id, pr_id) => {
    axios
        .post('https://www.kovskib.com/MW/RESTfulAPI/api/PROJECTMEDEWERKERS.php', {
            mw_id: mw_id,
            pr_id: pr_id
        }
        )
        .then(response => {
            if (response.status !== 200) {
                console.log('Error tijdens het posten van medewerker' + response.status)
            }
            console.log('Medewerker ' + mw_id + ' added to project ' + pr_id);
        });
};

const removeMedewerkerFromProject = (mw_id, pr_id) => {
    axios
        .delete('https://www.kovskib.com/MW/RESTfulAPI/api/PROJECTMEDEWERKERS.php', {
            data: {
                mw_id: mw_id,
                pr_id: pr_id
            }
        })
        .then(response => {
            if (response.status !== 200) {
                console.log('Error tijdens het posten van medewerker' + response.status)
            }
            console.log('Medewerker ' + mw_id + ' removed from project ' + pr_id);
        });
};
</script>

<style scoped>
.addToast::part(icon) {
    color: #2fdf75;
}

.deleteToast::part(icon) {
    color: #ff4961;
}
</style>