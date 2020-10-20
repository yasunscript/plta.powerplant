<template>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'home' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item active">{{ $route.meta.title }}</li>
        </ol>
    </div>
</template>

<script>
export default {};
</script>

<style lang="scss" scoped></style>
