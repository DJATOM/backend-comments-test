<template>
    <div
        v-if="!loaded"
    >
        Загрузка...
    </div>
    <div v-else
        class="comment-wrap"
    >
        <template
            v-if="comments.length > 0"
        >
            <comments-list
                :comments.sync="comments"
                @reload="reloadComments"
            />
            <div
                v-if="!writingComment"
                class="add-comment"
            >
                <a @click.stop="toggleComment">Добавить комментарий</a>
            </div>
        </template>
        <template v-else>
            <span
                v-if="!writingComment"
                class="no-comments"
            >
                Комментариев еще нет. <a @click.stop="toggleComment">Будьте первым!</a>
            </span>
        </template>
        <div
            v-if="writingComment"
            class="new-comment"
        >
            <textarea id="new-comment"></textarea>
            <div class="comment-actions">
                <a @click.stop="saveComment">Добавить</a>
                <a @click.stop="toggleComment">Отмена</a>
            </div>
        </div>
    </div>
</template>

<script>
import CommentsList from './components/CommentsList.vue'

export default {
    name    : 'CommentsApp',
    components: {
        CommentsList,
    },
    data () {
        return {
            comments: [],
            loaded: false,
            writingComment: false
        }
    },
    created () {
        this.getComments()
    },
    methods: {
        getComments () {
           this.loaded = false
           axios.get('/api/comments')
            .then((comments) => {
                this.comments = comments.data.data
                this.loaded   = true
            })
            .catch((error) => {
                this.loaded = true
            })
        },
        reloadComments () {
            this.writingComment = false
            this.getComments()
        },
        toggleComment () {
            this.writingComment = !this.writingComment
            console.log(this.writingComment)
        },
        saveComment () {
            const data = {
                content: document.querySelector('#new-comment').value,
            }
            axios.post(`/api/comments`, data)
                .then(() => {
                    this.$swal.fire('Комментарий добавлен!', '', 'success')
                    this.reloadComments()
                })
                .catch((error) => {
                    this.$swal(Object.values(error.response.data.errors).join('<br>'))
                })
        },
   }
}
</script>

<style>
    .comment-wrap {
        display: flex;
        flex-direction: column;
        width: fit-content;
        min-width: 550px;
    }
    .comment-container {
        display: flex;
        flex-direction: column;
        margin: 15px;
        border: 1px solid gray;
        padding: 10px;
    }
    .comment-body > textarea,
    .comment-reply {
        width: 100%;
    }
    .add-comment,
    .comment-actions {
        display: flex;
        margin-top: 5px;
        align-self: end;
    }
    .add-comment > a,
    .comment-actions > a {
        margin-right: 5px;
        border: 1px solid gray;
        padding: 5px;
    }
    .comment-actions > a,
    .no-comments > a,
    .add-comment > a {
        cursor: pointer;
        user-select: none;
    }
    .new-comment {
        display: flex;
        flex-direction: column;
        min-width: 500px;
        margin: 15px;
    }
    .add-comment {
        align-self: center;
    }
    .new-comment > textarea {
        min-height: 150px;
    }
    .new-comment > .comment-actions > a:last-child {
        margin-right: 0;
    }
    .comment-edit,
    .comment-reply {
        display: flex;
        flex-direction: column;
    }
</style>