## Diagrama de Entidade Relacionamento

```mermaid
classDiagram
direction BT
    class agent {
       varchar(100) name
       varchar(100) short_bio
       varchar(255) long_bio
       boolean culture
       created_at  /* (DC2Type:datetime_immutable) */ timestamp(0)
       timestamp(0) updated_at
       timestamp(0) deleted_at
       id  /* (DC2Type:uuid) */ uuid
    }
    class app_user {
       varchar(50) firstname
       varchar(50) lastname
       varchar(100) social_name
       varchar(100) email
       varchar(255) password
       varchar(20) status
       created_at  /* (DC2Type:datetime_immutable) */ timestamp(0)
       timestamp(0) updated_at
       timestamp(0) deleted_at
       id  /* (DC2Type:uuid) */ uuid
    }
    class doctrine_migration_versions {
       timestamp(0) executed_at
       integer execution_time
       varchar(191) version
    }
    class event {
       agent_group_id  /* (DC2Type:uuid) */ uuid
       space_id  /* (DC2Type:uuid) */ uuid
       initiative_id  /* (DC2Type:uuid) */ uuid
       parent_id  /* (DC2Type:uuid) */ uuid
       created_by_id  /* (DC2Type:uuid) */ uuid
       varchar(100) name
       json extra_fields
       varchar(255) image
       varchar(255) cover_image
       varchar(255) subtitle
       varchar(255) short_description
       text long_description
       smallint type
       timestamp(0) end_date
       varchar(255) site
       varchar(20) phone_number
       integer max_capacity
       smallint accessible_audio
       smallint accessible_libras
       boolean free
       jsonb social_networks
       boolean draft
       created_at  /* (DC2Type:datetime_immutable) */ timestamp(0)
       timestamp(0) updated_at
       timestamp(0) deleted_at
       id  /* (DC2Type:uuid) */ uuid
    }
    class initiative {
       varchar(100) name
       created_by_id  /* (DC2Type:uuid) */ uuid
       parent_id  /* (DC2Type:uuid) */ uuid
       space_id  /* (DC2Type:uuid) */ uuid
       created_at  /* (DC2Type:datetime_immutable) */ timestamp(0)
       timestamp(0) updated_at
       timestamp(0) deleted_at
       id  /* (DC2Type:uuid) */ uuid
    }
    class opportunity {
       varchar(100) name
       parent_id  /* (DC2Type:uuid) */ uuid
       space_id  /* (DC2Type:uuid) */ uuid
       initiative_id  /* (DC2Type:uuid) */ uuid
       event_id  /* (DC2Type:uuid) */ uuid
       created_by_id  /* (DC2Type:uuid) */ uuid
       created_at  /* (DC2Type:datetime_immutable) */ timestamp(0)
       timestamp(0) updated_at
       timestamp(0) deleted_at
       id  /* (DC2Type:uuid) */ uuid
    }
    class inscription_opportunity {
       agent_id  /* (DC2Type:uuid) */ uuid
       opportunity_id  /* (DC2Type:uuid) */ uuid
       integer status
       created_at  /* (DC2Type:datetime_immutable) */ timestamp(0)
       timestamp(0) updated_at
       timestamp(0) deleted_at
       id  /* (DC2Type:uuid) */ uuid
    }
    class phase {
       created_by_id  /* (DC2Type:uuid) */ uuid
       opportunity_id  /* (DC2Type:uuid) */ uuid
       varchar(100) name
       varchar(255) description
       timestamp(0) start_date
       timestamp(0) end_date
       boolean status
       integer sequence
       json extraFields
       json criteria
       created_at  /* (DC2Type:datetime_immutable) */ timestamp(0)
       timestamp(0) updated_at
       timestamp(0) deleted_at
       id  /* (DC2Type:uuid) */ uuid
    }
    class inscription_phase {
       agent_id  /* (DC2Type:uuid) */ uuid
       phase_id  /* (DC2Type:uuid) */ uuid
       integer status
       created_at  /* (DC2Type:datetime_immutable) */ timestamp(0)
       timestamp(0) updated_at
       timestamp(0) deleted_at
       id  /* (DC2Type:uuid) */ uuid
    }
    class inscription_phase_review {
       inscription_phase_id  /* (DC2Type:uuid) */ uuid
       reviewer_id  /* (DC2Type:uuid) */ uuid
       json result
       created_at  /* (DC2Type:datetime_immutable) */ timestamp(0)
       timestamp(0) updated_at
       timestamp(0) deleted_at
       id  /* (DC2Type:uuid) */ uuid
    }
    class organization {
       owner_id  /* (DC2Type:uuid) */ uuid
       created_by_id  /* (DC2Type:uuid) */ uuid
       varchar(100) name
       varchar(255) description
       varchar(20) type
       created_at  /* (DC2Type:datetime_immutable) */ timestamp(0)
       timestamp(0) updated_at
       timestamp(0) deleted_at
       id  /* (DC2Type:uuid) */ uuid
    }
    class organizations_agents {
       organization_id  /* (DC2Type:uuid) */ uuid
       agent_id  /* (DC2Type:uuid) */ uuid
    }
    class space {
        varchar(100) name
        varchar(255) image
        varchar(255) short_description
        text long_description
        varchar(255) cover_image
        varchar(255) site
        varchar(255) email
        varchar(20) phone_number
        integer max_capacity
        boolean is_accessible
        social_networks json
        extra_fields json
        boolean is_draft
        space_type_id /* (DC2Type:uuid) */ uuid
        created_by_id  /* (DC2Type:uuid) */ uuid
        parent_id  /* (DC2Type:uuid) */ uuid
        created_at  /* (DC2Type:datetime_immutable) */ timestamp(0)
        timestamp(0) updated_at
        timestamp(0) deleted_at
        id  /* (DC2Type:uuid) */ uuid
    }
    class phase_reviewers {
       phase_id  /* (DC2Type:uuid) */ uuid
       agent_id  /* (DC2Type:uuid) */ uuid
    }
    
    class entity_association {
        id  /* (DC2Type:uuid) */ uuid
        agent_id  /* (DC2Type:uuid) */ uuid
        event_id  /* (DC2Type:uuid) */ uuid
        initiative_id  /* (DC2Type:uuid) */ uuid
        opportunity_id  /* (DC2Type:uuid) */ uuid
        organization_id  /* (DC2Type:uuid) */ uuid
        space_id  /* (DC2Type:uuid) */ uuid
        boolean with_agent
        boolean with_event
        boolean with_initiative
        boolean with_opportunity
        boolean with_organization
        boolean with_space
    }
    class state {
       uuid id
       uuid capital_id
       varchar(100) name
       varchar(2) acronym
       varchar(20) region
    }
    class city {
       uuid id
       uuid state_id
       varchar(100) name
       integer city_code
    }
    class cultural_function {
        uuid id
        varchar(20) name
    }
    class event_type {
        uuid id
        varchar(20) name
    }

    city --> state : state_id
    event  -->  agent : created_by_id
    event  -->  agent : agent_group_id
    event  -->  event : parent_id
    event  -->  initiative : initiative_id
    event  -->  space : space_id
    initiative  -->  agent : created_by_id
    initiative  -->  initiative : parent_id
    initiative  -->  space : space_id
    opportunity  -->  agent : created_by_id
    opportunity  -->  event : event_id
    opportunity  -->  initiative : initiative_id
    opportunity  -->  opportunity : parent_id
    opportunity  -->  space : space_id
    organization  -->  agent : created_by_id
    organization  -->  agent : owner_id
    organizations_agents  -->  agent : agent_id
    organizations_agents  -->  organization : organization_id
    space  -->  agent : created_by_id
    space  -->  space : parent_id
    state --> city : capital_id
    inscription_opportunity --> agent : agent_id
    inscription_opportunity --> opportunity : opportunity_id
    phase --> agent : created_by_id
    phase --> opportunity : opportunity_id
    phase_reviewers --> phase : phase_id
    phase_reviewers --> agent : agent_id
    inscription_phase --> agent : agent_id
    inscription_phase --> phase : phase_id
    inscription_phase_review --> agent : reviewer_id
    inscription_phase_review --> inscription_phase : inscription_phase_id
    agent_cultural_function --> agent : agent_id
    agent_cultural_function --> cultural_function : cultural_function_id
    event --> event_type : event_type_id
```
> Esse diagrama serve como um diagrama de classes.

## Diagrama Entity Postgres X Document MongoDB

```mermaid
flowchart TD
    O[Organização] --> TLO[Timeline Organização]
    A[Agente] --> TLA[Timeline Agente]
    I[Iniciativa] --> TLI[Timeline Iniciativa]    
    OP[Oportunidade] --> TLOP[Timeline Oportunidade]
    E[Evento] --> TLE[Timeline Evento]
    S[Espaço] --> TLS[Timeline Espaço]
    EN[Entity] --> O
    EN --> A
    EN --> I
    EN --> S
    EN --> E
    EN --> OP
    U[Usuário] --API / Web--> EN
```
