<script setup lang="ts">
import type { WalletIndexProps } from '@/types/wallet'
import AppLayout from '@/layouts/AppLayout.vue'
import WalletCard from '@/components/wallet/WalletCard.vue'
import WalletActions from '@/components/wallet/WalletActions.vue'
import DepositModal from '@/components/wallet/DepositModal.vue'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import type { BreadcrumbItem } from '@/types'

defineProps<WalletIndexProps>()

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Carteira' },
]

const isDepositOpen = ref(false)
const isTransferOpen = ref(false)
</script>

<template>
    <Head title="Carteira" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4" >
            <WalletCard :wallet="wallet" />

            <div class="flex flex-col justify-center">
                <WalletActions
                    @deposit="isDepositOpen = true"
                    @transfer="isTransferOpen = true"
                />
            </div>
        </div>

        <DepositModal v-model:open="isDepositOpen" />
    </AppLayout>
</template>