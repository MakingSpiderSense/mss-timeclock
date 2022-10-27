<!-- Template -->
<template>
    <div>
        <div class="mb-6">Manage your organization invites below. Accepting an invite to an organization will give you access to the organization and its categories.</div>
        <!-- Invites -->
        <div class="invites">
            <!-- Invite -->
            <div class="invite">
                <div class="inv-org">Wayne Enterprise</div>
                <div class="inv-choice">
                    <button class="btn btn-success">Accept</button>
                    <button class="btn btn-danger">Decline</button>
                </div>
            </div>
            <!-- Invite -->
            <div class="invite">
                <div class="inv-org">Acme Corp</div>
                <div class="inv-choice">
                    <button class="btn btn-success">Accept</button>
                    <button class="btn btn-danger">Decline</button>
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Scripts -->
<script>
export default {
    name: 'InvitesManage',
}
</script>

<!-- Styling -->
<style lang="scss" scoped>
.invite {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 12px;
    &:hover {
        background-color: #f5f5f5;
    }
    .inv-org {
        font-weight: 600;
    }
    .inv-choice {
        display: flex;
        button {
            margin-left: 10px;
            padding: 5px 20px;
        }
    }
}
</style>