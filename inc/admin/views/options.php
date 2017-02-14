<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

    <h2>Orders Algolia index</h2>
    <p class="submit">
        <button class="aos-reindex-button button button-primary">Re-index orders</button>
    </p>

    <h2>Algolia Account settings</h2>

    <form method="post">
        <table class="form-table">
            <tbody>
                <tr>
                    <th>
                        <label>Algolia Application ID: </label>
                    </th>
                    <td>
                        <input type="text" class="regular-text" name="wcos_alg_app_id" value="<?php echo esc_attr($this->options->getAlgoliaAppId()); ?>">
                    </td>
                </tr>
                <tr>
                    <th>
                        <label>Algolia Search API key:</label>
                    </th>
                    <td>
                        <input type="text" class="regular-text" name="wcos_alg_search_api_key" value="<?php echo esc_attr($this->options->getAlgoliaSearchApiKey()); ?>">
                        <p class="description">Make sure this key is not used for frontend search!</p>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label>Algolia Admin API key:</label>
                    </th>
                    <td>
                        <input type="password" class="regular-text" name="wcos_alg_admin_api_key" value="<?php echo esc_attr($this->options->getAlgoliaAdminApiKey()); ?>">
                    </td>
                </tr>
            </tbody>
        </table>

        <p class="submit">
            <button type="submit" class="button button-primary">Save Algolia account settings</button>
        </p>
    </form>




    <h2>Orders indexing settings</h2>

    <form method="post">
        <table class="form-table">
            <tbody>
            <tr>
                <th>
                    <label>Orders index name in Algolia:</label>
                </th>
                <td>
                    <input type="text" class="regular-text" name="wcos_orders_index_name" value="<?php echo esc_attr($this->options->getOrdersIndexName()); ?>">
                </td>
            </tr>
            <tr>
                <th>
                    <label>Orders to index per batch:</label>
                </th>
                <td>
                    <input type="number" name="wcos_orders_per_batch"  value="<?php echo esc_attr($this->options->getOrdersToIndexPerBatchCount()); ?>">
                </td>
            </tr>
            </tbody>
        </table>

        <p class="submit">
            <button type="submit" class="button button-primary">Save orders indexing settings</button>
        </p>
    </form>

</div>
