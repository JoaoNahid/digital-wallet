<script setup lang="ts">
import type { Wallet } from '@/types/wallet'
import { useWallet } from '@/composables/useWallet'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { WalletIcon } from 'lucide-vue-next'

interface Props {
    wallet: Wallet
}

const props = defineProps<Props>()

const { formattedBalance, isNegative, balanceColorClass } = useWallet(() => props.wallet)

</script>

<template>
    <Card>
        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium">Saldo Disponível</CardTitle>
            <WalletIcon class="h-4 w-4 text-muted-foreground" />
        </CardHeader>
        <CardContent>
            <div class="text-3xl font-bold" :class="balanceColorClass">
                {{ formattedBalance }}
            </div>
            <p v-if="isNegative" class="text-xs text-destructive mt-1">
                Seu saldo está negativo
            </p>
        </CardContent>
    </Card>
</template>