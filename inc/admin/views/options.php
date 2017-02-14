<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

    <h2>Orders Algolia index</h2>
    <p class="submit">
        <button class="aos-reindex-button button button-primary">Re-index orders</button>
    </p>

    <h2>Algolia Account settings</h2>
    <p>This plugin indexes your orders in <a href="https://www.algolia.com/" target="_blank">Algolia</a> to get extremely fast an relevant results.</p>
    <p>Algolia is a hosted search service that offers <a href="https://www.algolia.com/pricing" target="_blank">different pricing plans</a> according to your usage.</p>
    <p>In this plugin, every un-trashed order will be stored as one record in Algolia.</p>
    <p>If you <strong>don't have an Algolia account yet</strong>, you can <a href="https://www.algolia.com/users/sign_up" target="_blank">create one in a few minutes</a>.</p>

    <form method="post" class="aos-ajax-form">
        <input type="hidden" name="action" value="aos_save_algolia_settings">
        <table class="form-table">
            <tbody>
                <tr>
                    <th>
                        <label>Algolia Application ID: </label>
                    </th>
                    <td>
                        <input type="text" class="regular-text" name="app_id" value="<?php echo esc_attr($this->options->getAlgoliaAppId()); ?>">
                        <p class="description">You can grab it from your <a href="https://www.algolia.com/api-keys" target="_blank">Algolia admin panel</a>.</p>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label>Algolia Search API key:</label>
                    </th>
                    <td>
                        <input type="text" class="regular-text" name="search_api_key" value="<?php echo esc_attr($this->options->getAlgoliaSearchApiKey()); ?>">
                        <p class="description">
                            You can grab it from your <a href="https://www.algolia.com/api-keys" target="_blank">Algolia admin panel</a>.
                            <br>
                            For maximum security, this key should only have "search" permission on the "<?php echo esc_attr($this->options->getOrdersIndexName()); ?>" index.
                            <br>
                            Read more about permissions in the <a href="https://www.algolia.com/doc/guides/security/api-keys/" target="_blank">Algolia guide about API keys</a>.
                        </p>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label>Algolia Admin API key:</label>
                    </th>
                    <td>
                        <input type="password" class="regular-text" name="admin_api_key" value="<?php echo esc_attr($this->options->getAlgoliaAdminApiKey()); ?>">
                        <p class="description">You can grab it from your <a href="https://www.algolia.com/api-keys" target="_blank">Algolia admin panel</a>.</p>
                    </td>
                </tr>
            </tbody>
        </table>

        <p class="submit">
            <button type="submit" class="button button-primary">Save Algolia account settings</button>
        </p>
    </form>




    <h2>Orders indexing settings</h2>

    <form method="post" class="aos-ajax-form">
        <input type="hidden" name="action" value="aos_save_indexing_options">
        <table class="form-table">
            <tbody>
            <tr>
                <th>
                    <label>Orders index name in Algolia:</label>
                </th>
                <td>
                    <input type="text" class="regular-text" name="orders_index_name" value="<?php echo esc_attr($this->options->getOrdersIndexName()); ?>">
                </td>
            </tr>
            <tr>
                <th>
                    <label>Orders to index per batch:</label>
                </th>
                <td>
                    <input type="number" name="orders_per_batch"  value="<?php echo esc_attr($this->options->getOrdersToIndexPerBatchCount()); ?>">
                </td>
            </tr>
            </tbody>
        </table>

        <p class="submit">
            <button type="submit" class="button button-primary">Save orders indexing settings</button>
        </p>
    </form>

</div>
