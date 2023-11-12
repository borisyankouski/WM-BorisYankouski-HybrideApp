<template>
    <ion-card :data-mw_id=mw_id :data-sp_id=sp_id>
        <ion-card-header>
            <ion-card-title>{{ Voornaam }} {{ Familienaam }}</ion-card-title>
            <ion-card-subtitle>{{ Specialisatie }}</ion-card-subtitle>
        </ion-card-header>

        <!-- <ion-card-content>
            <ion-button>Beheer Projecten</ion-button>
        </ion-card-content> -->
        <ion-row>
            <ion-button fill="clear" color="tertiary" aria-label="Favorite">
                Beheer <ion-icon slot="end" :icon="cogOutline" color="tertiary"></ion-icon>
            </ion-button>
            <ion-button fill="clear" color="danger" aria-label="Favorite">
                Verwijder <ion-icon slot="end" :icon="trashOutline" color="danger"></ion-icon>
            </ion-button>
        </ion-row>
    </ion-card>
</template>
  
<script setup>
import { IonCard, IonCardContent, IonCardHeader, IonCardSubtitle, IonCardTitle, IonButton, IonIcon, IonRow } from '@ionic/vue';
import { cogOutline, trashOutline } from 'ionicons/icons';

defineProps({
    Voornaam: String,
    Familienaam: String,
    Specialisatie: String,
    sp_id: Number,
    mw_id: Number
});
</script>