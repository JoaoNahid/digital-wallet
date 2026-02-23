<script setup lang="ts">
import type { Transaction } from '@/types/wallet'
import TransactionItem from './TransactionItem.vue'
import EmptyState from '@/components/shared/EmptyState.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { ReceiptIcon } from 'lucide-vue-next'

interface Props {
    transactions: Transaction[]
    title?: string
    showEmpty?: boolean
}

withDefaults(defineProps<Props>(), {
    title: 'Transações',
    showEmpty: true,
})
</script>

<template>
    <Card>
        <CardHeader v-if="title">
            <CardTitle>{{ title }}</CardTitle>
        </CardHeader>
        <CardContent class="p-0">
            <div v-if="transactions.length > 0" class="divide-y">
                <TransactionItem
                    v-for="transaction in transactions"
                    :key="transaction.id"
                    :transaction="transaction"
                />
            </div>
            <div v-else-if="showEmpty" class="p-6">
                <EmptyState
                    :icon="ReceiptIcon"
                    title="Nenhuma transação"
                    description="Suas transações aparecerão aqui."
                />
            </div>
        </CardContent>
    </Card>
</template>