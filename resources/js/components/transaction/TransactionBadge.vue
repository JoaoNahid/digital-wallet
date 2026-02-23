<script setup lang="ts">
import type { TransactionStatus } from '@/types/wallet'
import { Badge } from '@/components/ui/badge'
import { computed } from 'vue'

interface Props {
    status: TransactionStatus
    label: string
    variant: 'warning' | 'success' | 'secondary' | 'destructive'
}

const props = defineProps<Props>()

const badgeVariant = computed(() => {
    const variantMap: Record<string, 'default' | 'secondary' | 'destructive' | 'outline'> = {
        warning: 'outline',
        success: 'default',
        secondary: 'secondary',
        destructive: 'destructive',
    }
    return variantMap[props.variant] || 'default'
})
</script>

<template>
    <Badge :variant="badgeVariant">
        {{ label }}
    </Badge>
</template>