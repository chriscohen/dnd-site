<template>
    <div id="outer" class="admin">
        <div id="header">
            Placeholder
        </div>
        <div id="page">
            <NuxtLoadingIndicator/>
            <slot/>
        </div>
    </div>
</template>

<style lang="scss" scoped>
#header {
    display: flex;
    justify-content: space-between;
}
</style>
