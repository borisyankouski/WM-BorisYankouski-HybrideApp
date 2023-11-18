<template>
  <ion-page>
    <ion-tabs mode="ios">
      <ion-router-outlet></ion-router-outlet>
      <ion-tab-bar slot="bottom" :translucent="true">
        <ion-tab-button tab="tab1" href="/tabs/tab1">
          <ion-icon aria-hidden="true" :icon="peopleOutline" />
          <ion-label>Medewerkers</ion-label>
        </ion-tab-button>

        <ion-tab-button tab="tab2" href="/tabs/tab2">
          <ion-icon aria-hidden="true" :icon="briefcaseOutline" />
          <ion-label>Projecten</ion-label>
        </ion-tab-button>

        <ion-tab-button tab="tab3" href="/tabs/tab3">
          <ion-icon aria-hidden="true" :icon="informationCircleOutline" />
          <ion-label>About</ion-label>
        </ion-tab-button>
      </ion-tab-bar>
    </ion-tabs>
  </ion-page>
</template>

<script setup>
import { IonTabBar, IonTabButton, IonTabs, IonLabel, IonIcon, IonPage, IonRouterOutlet } from '@ionic/vue';
import { briefcaseOutline, informationCircleOutline, peopleOutline } from 'ionicons/icons';
</script>
