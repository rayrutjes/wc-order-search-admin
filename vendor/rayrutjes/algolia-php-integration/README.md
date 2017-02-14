Algolia PHP Integration
-----------------------

Library that eases development of PHP integrations.

Current Problems
----------------

6. Problem is that we rely on indexmanager everywhere in commands, but for commands such as deleteIndex maybe the index isn't managed anymore
best would be to rename IndexManager -> IndexRepository
then all indices should be lazy loaded, by injecting IndexFactory for example 
remove the all() method?

7. find the right place to inflect.

8. find the best usage for commands


Ideas
-----
- ReIndexUsingTemporaryIndex -> keep production settings option ?
- DeleteIndex -> delete replicas option ?
- Add a way to log stuff in algolia service (LoggingAlgoliaService)
- only keep 'execute' on service adn extract interface to be able to use custom LoggingAlgoliaService i.e.


IndexManager
- takes non inflected name
- returns index

-> index manager holds the bindings between inflected and non inflected?

Problem is that to generate commands, index should know its local name

Maybe store local & distant name?
