<script setup lang="ts">
import type { WalletIndexProps } from '@/types/wallet'
import AppLayout from '@/layouts/AppLayout.vue'
import WalletCard from '@/components/wallet/WalletCard.vue'
import WalletActions from '@/components/wallet/WalletActions.vue'
import TransactionList from '@/components/transaction/TransactionList.vue'
import DepositModal from '@/components/wallet/DepositModal.vue'
import TransferModal from '@/components/wallet/TransferModal.vue'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import type { BreadcrumbItem } from '@/types'
import { useWallet } from '@/composables/useWallet'
import Button from '@/components/ui/button/Button.vue'

const props = defineProps<WalletIndexProps>()

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Carteira' },
]

const isDepositOpen = ref(false)
const isTransferOpen = ref(false)
const pagination = props.transactions.meta
const { goToPage } = useWallet(props.wallet)
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

            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold px-4">Extrato</h2>
            </div>
            <TransactionList
                :transactions="transactions.data"
                title="Todas as Transações"
            />

            <!-- Pagination -->
            <div v-if="pagination.last_page > 1" class="flex items-center justify-between">
                <p class="text-sm text-muted-foreground">
                    Mostrando {{ transactions.data.length }} de {{ pagination.total }} transações
                </p>

                <div class="flex gap-2">
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="pagination.current_page === 1"
                        @click="goToPage(pagination.current_page - 1)"
                    >
                        <ChevronLeftIcon class="h-4 w-4" />
                        Anterior
                    </Button>

                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="pagination.current_page === pagination.last_page"
                        @click="goToPage(pagination.current_page + 1)"
                    >
                        Próxima
                        <ChevronRightIcon class="h-4 w-4" />
                    </Button>
                </div>
            </div>
        </div>

        <DepositModal v-model:open="isDepositOpen" />
        <TransferModal v-model:open="isTransferOpen" :wallet="wallet" />
    </AppLayout>
</template>