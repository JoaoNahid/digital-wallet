import type { Wallet } from '@/types/wallet'
import { computed, toRef, type MaybeRefOrGetter } from 'vue'
import { router } from '@inertiajs/vue3'


export function useWallet(wallet: MaybeRefOrGetter<Wallet>) {
    const walletRef = toRef(wallet)

    const formattedBalance = computed(() => walletRef.value.formatted_balance)

    const isNegative = computed(() => walletRef.value.is_negative)

    const balanceColorClass = computed(() =>
        isNegative.value ? 'text-destructive' : 'text-foreground'
    )

    function goToPage(page: number) {
        router.get(
            '/wallet',
            {
                page
            },
            {
                preserveState: true,
                preserveScroll: true,
            }
        )
    }

    return {
        wallet: walletRef,
        formattedBalance,
        isNegative,
        balanceColorClass,
        goToPage
    }
}