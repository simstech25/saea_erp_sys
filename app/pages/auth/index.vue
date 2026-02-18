<script setup lang="ts">
import type { AuthFormField } from '@nuxt/ui';

const fields = ref<AuthFormField[]>([
    {
        name: 'email',
        type: 'email',
        label: 'Email'
    },
    {
        name: 'password',
        type: 'password',
        label: 'Password'
    }
])
</script>
<template>
    <UAuthForm title="Login" description="Enter your credentials to access your account." icon="i-lucide-user"
        :fields="fields" class="max-w-md" />
</template>