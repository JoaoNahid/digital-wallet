import type { Wallet } from '@/types/wallet'
import { computed, toRef, type MaybeRefOrGetter } from 'vue'

export function useWallet(wallet: MaybeRefOrGetter<Wallet>) {
    const walletRef = toRef(wallet)

    const formattedBalance = computed(() => walletRef.value.formatted_balance)

    const isNegative = computed(() => walletRef.value.is_negative)

    const balanceColorClass = computed(() =>
        isNegative.value ? 'text-destructive' : 'text-foreground'
    )

    return {
        wallet: walletRef,
        formattedBalance,
        isNegative,
        balanceColorClass,
    }
}