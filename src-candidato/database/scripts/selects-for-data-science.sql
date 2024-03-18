select 
	u.created_at as data_criacao_usuario,
	s.is_homologated as homologado,	
	case when s.elimination is null then false
		 else true
	end as eliminado,
    case when aa.is_ppi = true and s.is_ppi_checked = true then true 
    	 when aa.is_ppi = true and s.is_ppi_checked = false then false 
    	 else null
    end as ppi_verificado_2,
	s.created_at as data_inscricao,
	n."number" as edital,	
	m.description as modalidate,
	c.has_whatsapp as tem_whatsapp,
	c.has_telegram as tem_telegram,
	c2."name" as cidade,
	s2.slug as uf,	
	aa.slug as acao_afirmativa,
	aa.is_ppi as acao_afirmativa_eh_ppi,
	aa.is_wide_competition as acao_afirmativa_eh_ampla,
	c3."name" as campus,
	c4."name" as curso,
	call_int.is_wide_concurrency as passou_ampla_integrado,
	call_int.migration_vacancy_map_id as vaga_migrada_integrado,
	call_int.status as status_chamada_integrado,
	call_sub.is_wide_concurrency as passou_ampla_sub,
	call_sub.migration_vacancy_map_id as vaga_migrada_sub,
	call_sub.status as status_chamada_sub,
	call_sup_enem.migration_vacancy_map_id as vaga_migrada_sup_enem,
	call_sup_enem.is_wide_concurrency as passou_ampla_sup_enem,
	call_sup_enem.status as status_chamada_sup_enem,
	call_sup_outr.migration_vacancy_map_id as vaga_migrada_sup_outros,
	call_sup_outr.is_wide_concurrency as passou_ampla_sup_outros,
	call_sup_outr.status as status_chamada_sup_outros,
	score_sup_enem.media as media_enem,
	score_sup_outros.media as media_outros
	from core.users u
left join 
	core.subscriptions s on s.user_id = u.id
left join 
	core.notices n on n.id = s.notice_id
left join
	core.modalities m on m.id = n.modality_id 
left join 
	core.contacts c on c.user_id = u.id
left join
	core.distribution_of_vacancies dov on dov.id = s.distribution_of_vacancies_id
left join 
	core.affirmative_actions aa on aa.id = dov.affirmative_action_id 
left join 
	core.offers o on o.id = dov.offer_id 
left join
	core.course_campus_offers cco on cco.id = o.course_campus_offer_id 
left join 
	core.campuses c3 on c3.id = cco.campus_id 
left join 
	core.courses c4 on c4.id = cco.course_id 
left join
	core.cities c2 on c2.id = c.city_id 
left join
	core.states s2 on s2.id = c2.state_id
left join 
	notice_1.criteria_1_call call_int on call_int.subscription_id = s.id 
left join 
	notice_6.criteria_1_call call_sub on call_sub.subscription_id = s.id 
left join
	notice_7.criteria_3_call call_sup_enem on call_sup_enem.subscription_id = s.id
left join 
	notice_7.criteria_4_call call_sup_outr on call_sup_outr.subscription_id = s.id
left join 
	notice_7.criteria_3_score score_sup_enem on score_sup_enem.subscription_id = s.id 
left join
	notice_7.criteria_4_score score_sup_outros on score_sup_outros.subscription_id = s.id;
	
select count(*) from core.users s 