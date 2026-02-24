<script setup lang="ts">
import { Card, CardContent } from '@/components/ui/card'
import type { LucideIcon } from 'lucide-vue-next'
import { InboxIcon } from 'lucide-vue-next'

interface Props {
    icon?: LucideIcon
    title: string
    description?: string
}

withDefaults(defineProps<Props>(), {
    icon: () => InboxIcon,
})
</script>

<template>
    <Card class="border-dashed">
        <CardContent class="flex flex-col items-center justify-center py-12 text-center">
            <component :is="icon" class="h-12 w-12 text-muted-foreground mb-4" />
            <h3 class="text-lg font-medium">{{ title }}</h3>
            <p v-if="description" class="text-sm text-muted-foreground mt-1">
                {{ description }}
            </p>
            <div class="mt-4">
                <slot />
            </div>
        </CardContent>
    </Card>
</template>